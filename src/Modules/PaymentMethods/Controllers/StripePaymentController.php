<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use MissionControl\Payments\Modules\PaymentMethods\Models\Stripe;
use MissionControl\Payments\Modules\Transactions\Models\Transaction as PaymentTransaction;
use Modules\Users\Models\User;
use Stripe\Error\Card;
use Stripe\Error\RateLimit;
use Stripe\Error\InvalidRequest;
use Stripe\Error\Authentication;
use Stripe\Error\ApiConnection;
use Stripe\Error\Base;
use Stripe\Error\SignatureVerification;

/**
 * Class StripePaymentController
 * @package MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class StripePaymentController
{
    /**
     * @var
     */
    private $paymentMethod;
    
    /**
     * @var \MissionControl\Payments\Modules\PaymentMethods\Models\Stripe
     */
    private $model;
    
    /**
     * StripePaymentController constructor.
     *
     * @param $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->model = new Stripe();
        $this->model->stripe()->setApiKey($paymentMethod->details->secret_key);
    }
    
    /**
     * @param      $object
     * @param bool $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay($object, $token = false)
    {
        // create transaction
        $transaction = new PaymentTransaction();
        $transaction->user_id = (auth()->check() ? auth()->user()->id : null);
        $transaction->paymentmethod_id = $this->paymentMethod->id;
        $transaction->reference = $transaction->generateReference();
        $transaction->type_id = $object->id;
        $transaction->type = get_class($object);
        $transaction->currency = $object->currency;
        $transaction->subtotal = $object->subtotal;
        $transaction->total = $object->total;
        $transaction->vat = $object->vat;

        try {
            // store stripe
            $payment = $this->model->charge()->create([
                'amount'   => $object->total * 100,
                'currency' => $object->currency,
                'source'   => $token
            ]);

            // save transaction
            $transaction->status = 'complete';
            $transaction->vendor_reference = $payment->id;

        } catch(Card $e) {

            $errors = $e->getJsonBody()['error'];
            $transaction->status = 'failed';
            $transaction->vendor_reference = $errors['charge'];
            $transaction->error_code = $errors['code'];
            $transaction->error_message = $errors['message'];

        } catch(RateLimit $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        } catch(InvalidRequest $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        } catch(Authentication $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        } catch(ApiConnection $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        } catch(Base $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        } catch(Exception $e) {

            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();

        }

        // save the transaction
        $transaction->save();

        if($transaction->status == 'complete') {
            return redirect()->route('payment.complete', $transaction->reference);
        }

        // redirect failed
        return redirect()->route('payment.failed', $transaction->reference);
    }
    
    /**
     * @param $transaction
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function complete($transaction)
    {
        $parentObject = (new $transaction->type)->find($transaction->type_id);
        return view('Payments::Frontend.payment-complete', compact('transaction', 'parentObject'));
    }
    
    /**
     * @param $transaction
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failed($transaction)
    {
        $parentObject = (new $transaction->type)->find($transaction->type_id);
        return view('Payments::Frontend.payment-failed', compact('transaction', 'parentObject'));
    }
    
    /**
     * @return int
     */
    public function webhooks()
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                request()->getContent(),
                request()->server('HTTP_STRIPE_SIGNATURE'),
                $this->paymentMethod->details->webhook_secret
            );

            if($transaction = PaymentTransaction::where('vendor_reference', $event->data->object->id)->first()) {
                switch($event->type) {
                    case 'charge.succeeded':
                        $transaction->status = 'complete';
                        $transaction->save();
                        break;
                    case 'charge.failed':
                        $transaction->status = 'failed';
                        $transaction->error_code = $event->data->object->failure_code;
                        $transaction->error_message = $event->data->object->failure_message;
                        $transaction->save();
                        break;
                    case 'charge.refunded':
                        $transaction->status = 'refunded';
                        $transaction->save();
                        break;
                }
            }

            return http_response_code(200);

        } catch(\UnexpectedValueException $e) {

            return http_response_code(400);

        } catch(SignatureVerification $e) {

            return http_response_code(400);
        }
    }
}
