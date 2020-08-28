<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use Carbon\Carbon;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;
use MissionControl\Payments\Modules\Transactions\Models\Transaction as PaymentTransaction;
use Modules\Users\Models\User;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Sample\PayPalClient;
use BraintreeHttp\HttpException;

/**
 * Class PaypalPaymentController
 * @package MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class PaypalPaymentController
{
    /**
     * @var paymentMethod
     */
    private $paymentMethod;

    /**
     * @var ProductionEnvironment|SandboxEnvironment
     */
    private $environment;

    /**
     * @var PayPalHttpClient
     */
    private $paypalClient;

    /**
     * PaypalPaymentController constructor.
     * @param $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->environment = ($this->paymentMethod->mode == 'live' ? new ProductionEnvironment($this->paymentMethod->details->client_id, $this->paymentMethod->details->client_secret) : new SandboxEnvironment($this->paymentMethod->details->client_id, $this->paymentMethod->details->client_secret));
        $this->paypalClient = new PayPalHttpClient($this->environment);
    }

    /**
     * @param $object
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay($object, $token = false)
    {
        // build order request
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING' // dont let them choose an address in the popup
            ],
            'purchase_units' => [
                0 =>
                    [
                        'custom_id' => $object->reference,
                        'description' => 'Order ' . $object->present()->getReference . ' from ' . app('ecommercesettings')->company_name,
                        'soft_descriptor' => app('ecommercesettings')->company_name,
                        'amount' => [
                            'currency_code' => $object->currency,
                            'value' => $object->total
                        ],
                    ]
            ],
        ];

        try {
            // excute the payment request
            $response = $this->paypalClient->execute($request);

            // create the transaction
            $transaction = new PaymentTransaction();
            $transaction->user_id = (auth()->check() ? auth()->user()->id : null);
            $transaction->paymentmethod_id = $this->paymentMethod->id;
            $transaction->reference = $transaction->generateReference();
            $transaction->vendor_reference = $response->result->id;
            $transaction->type_id = $object->id;
            $transaction->type = get_class($object);
            $transaction->currency = $object->currency;
            $transaction->subtotal = $object->subtotal;
            $transaction->total = $object->total;
            $transaction->vat = $object->vat;
            $transaction->save();

            // return the response as json
            return response()->json($response);

        } catch (HttpException $e) {
            // there was an error
            return response()->json([
                'error' => [
                    'message' => 'There was a problem with PayPal, please try again.'
                ]
            ]);
        }
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function capture($transaction)
    {
        // create the order capture request
        $request = new OrdersCaptureRequest($transaction->vendor_reference);

        try {
            // excute capture request
            $response = $this->paypalClient->execute($request);

            // update transaction
            $transaction->status = 'complete';
            $transaction->save();

            // return redirect url
            return response()->json(['url' => route('payment.complete', $transaction->reference)]);

        } catch (HttpException $e) {
            // there was an error
            $transaction->status = 'failed';
            $transaction->error_code = $e->getCode();
            $transaction->error_message = $e->getMessage();
            $transaction->save();

            // return redirect url
            return response()->json(['url' => route('payment.failed', $transaction->reference)]);
        }
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
}
