<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use Carbon\Carbon;
use MissionControl\Payments\Modules\Packages\Models\Package;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;
use MissionControl\Payments\Modules\PaymentMethods\Models\Paypal;
use MissionControl\Payments\Modules\Transactions\Models\Transaction as PaymentTransaction;
use Modules\Users\Models\User;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;

/**
 * Class PaypalSubscriptionController
 * @package MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class PaypalSubscriptionController
{
    /**
     * @var
     */
    private $paymentMethod;

    /**
     * @var ApiContext
     */
    private $api;

    /**
     * @var Paypal
     */
    private $model;

    /**
     * Paypal constructor.
     * @param $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->model = new Paypal();
        $this->api = $this->model->ApiContext($paymentMethod->details->client_id, $paymentMethod->details->client_secret);
        $this->api->setConfig(array(
            'mode' => (($paymentMethod->mode == 'test') ? 'sandbox' : 'live'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => base_path('storage/logs') . '/PayPal.log',
            'log.LogLevel' => 'FINE',
            'return_url' => route('home')
        ));
    }

    /**
     * @param $package
     * @return mixed
     */
    public function subscribe($package)
    {
        /**
         * Create the agreement
         */
        $agreement = $this->model->agreement();
        $agreement->setName($package->name);
        $agreement->setDescription($package->description);
        $agreement->setStartDate(Carbon::now()->addMonth(1)->toAtomString());
        $payer = $this->model->payer();
        $payer->setPaymentMethod("paypal");
        $agreement->setPlan(['id' => $package->paypal_id]);
        $agreement->setPayer($payer);
        $response = $agreement->create($this->api);
        $redirectUrl = $agreement->getApprovalLink();
        // parse the URL to get the token in order to save it in the database
        parse_str(parse_url($redirectUrl, PHP_URL_QUERY), $parsedUrl);

        // create a pending transaction ready for the callback
        $transaction = new PaymentTransaction();
        $transaction->create([
            'user_id' => auth()->user()->id,
            'type_id' => $package->id,
            'type' => get_class($package),
            'reference' => $transaction->generateReference(),
            'vendor_reference' => $parsedUrl['token'],
            'status' => 'pending',
            'subtotal' => $package->price,
            'vat' => $package->price,
            'total' => $package->price
        ]);

        $user = auth()->user();
        $user->subscription_method_id = $this->paymentMethod->id;
        $user->subscription_package_id = $package->id;
        $user->subscription_reference = $response->getId();
        $user->save();

        return view('Packages::Frontend.redirect', [
            'redirectUrl' => $redirectUrl,
            'paymentMethod' => $this->paymentMethod
        ]);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function complete($request)
    {
        // get the agreement by the token
        $agreement = $this->model->agreement()->execute($request['token'], $this->api);

        $user = auth()->user();
        $user->subscription_method_id = $this->paymentMethod->id;
        $user->subscription_reference = $agreement->getId();
        $user->save();

        return view('Packages::Frontend.complete');
    }

    /**
     * @param $package
     */
    public function upgrade($package)
    {
        $user = auth()->user();

        // cancel current subscription
        $agreement = $this->model->agreement()->get($user->subscription_reference, $this->api);
        if($agreement->getState() != 'Cancelled') {
            $agreementStateDescriptor = $this->model->agreementStateDescriptor();
            $agreementStateDescriptor->setNote('Cancelling');
            $agreement->cancel($agreementStateDescriptor, $this->api);

            $user->subscription_cancelled_at = Carbon::now();
            $user->update();
        }

        $this->subscribe($package->id);
    }

    /**
     * @return mixed
     */
    public function cancel()
    {
        $user = auth()->user();

        $agreement = $this->model->agreement()->get($user->subscription_reference, $this->api);
        if($agreement->getState() == 'Cancelled') {
            return redirect()
                ->route('subscriptions.index')
                ->with('flash_message', 'Already Cancelled')
                ->with('flash_message_type', 'error');
        }

        $agreementStateDescriptor = $this->model->agreementStateDescriptor();
        $agreementStateDescriptor->setNote('Cancel');
        $agreement->cancel($agreementStateDescriptor, $this->api);

        $user->subscription_cancelled_at = Carbon::now();
        $user->update();

        return redirect()
            ->route('subscriptions.index')
            ->with('flash_message', 'Successfully cancelled.')
            ->with('flash_message_type', 'success');
    }

    /**
     * @param $request
     */
    public function webhooks($request)
    {
        $signatureVerification = $this->model->verifywebhooksignature()
            ->setAuthAlgo($request->header('PAYPAL-AUTH-ALGO'))
            ->setTransmissionId($request->header('PAYPAL-TRANSMISSION-ID'))
            ->setCertUrl($request->header('PAYPAL-CERT-URL'))
            ->setWebhookId($this->paymentMethod->details->webhook_id)// Note that the Webhook ID must be a currently valid Webhook that you created with your client ID/secret.
            ->setTransmissionSig($request->header('PAYPAL-TRANSMISSION-SIG'))
            ->setTransmissionTime($request->header('PAYPAL-TRANSMISSION-TIME'))
            ->setRequestBody($request->getContent())
            ->post($this->api);

        // its valid, now we can start updating things
        if($signatureVerification->getVerificationStatus() == 'SUCCESS') {

            $paymentMethod = new PaymentMethod();
            $requestContent = json_decode($request->getContent());
            switch($requestContent->event_type) {
                case 'BILLING.SUBSCRIPTION.CREATED':
                    if($user = User::where('subscription_reference', $requestContent->resource->id)->first()) {
                        $paymentMethod->subscriptionStarted($user, $requestContent->create_time, $requestContent->resource->agreement_details->next_billing_date);
                    }
                    break;
                case 'PAYMENT.SALE.COMPLETED':
                    if($user = User::where('subscription_reference', $requestContent->resource->billing_agreement_id)->first()) {
                        if($package = Package::where('id', $user->subscription_package_id)->withTrashed()->first()) {
                            $paymentMethod->paymentReceived($user, $package, $requestContent->resource->id, $requestContent->resource->state == 'completed' ? 'complete' : 'pending', $requestContent->resource->amount->total, $requestContent->resource->amount->total, $requestContent->resource->amount->total);
                        }
                    }
                    break;
                case 'BILLING.SUBSCRIPTION.CANCELLED':
                    if($user = User::where('subscription_reference', $requestContent->resource->id)->first()) {
                        $paymentMethod->subscriptionCancelled($user, $requestContent->create_time);
                    }
                    break;
            }
        }
    }
}
