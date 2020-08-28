<?php namespace MissionControl\Payments\Modules\Packages\Models;

use MissionControl\Payments\Modules\PaymentMethods\Models\Paypal;
use PayPal\Exception\PayPalConnectionException;

/**
 * Class PaypalPackage
 * @package MissionControl\Payments\Modules\Packages\Models
 */
class PaypalPackage
{
    /**
     * @var Paypal
     */
    private $paypal;

    /**
     * @var ApiContext
     */
    private $api;

    /**
     * @var
     */
    private $paymentMethod;

    /**
     * PaypalPackage constructor.
     * @param $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->paypal = new Paypal();
        $this->api = $this->paypal->ApiContext($this->paymentMethod->details->client_id, $this->paymentMethod->details->client_secret);
        $this->api->setConfig(array(
            'mode' => (($this->paymentMethod->mode == 'test') ? 'sandbox' : 'live'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => base_path('storage/logs') . '/PayPal.log',
            'log.LogLevel' => 'FINE',
            'return_url' => route('subscriptions.complete'),
        ));
    }

    /**
     * @param Package $package
     * @return string
     */
    public function create(Package $package)
    {
        $plan = $this->paypal->plan();
        $plan->setName($package->name);
        $plan->setDescription($package->present()->getPrice . '/' . $package->present()->getHumanFrequency . ' subscription to Helperfirst');
        $plan->setType('infinite');
        $plan->setState(($package->active ? 'ACTIVE' : 'INACTIVE'));
        $paymentDefinition = $this->paypal->paymentDefinition();
        $paymentDefinition->setName($package->name);
        $paymentDefinition->setType('REGULAR');
        $paymentDefinition->setFrequency($package->frequency);
        $paymentDefinition->setFrequencyInterval($package->frequency_interval);
        $amount = $this->paypal->currency()->setCurrency($package->currency)->setValue($package->price);
        $paymentDefinition->setAmount($amount);
        $paymentDefinition->setCycles("0");
        $plan->setPaymentDefinitions([$paymentDefinition]);
        $merchantPreferences = $this->paypal->merchantPreferences();
        $setupfee = $this->paypal->currency()->setCurrency($package->currency)->setValue($package->price);
        $merchantPreferences->setSetupFee($setupfee);
        $merchantPreferences->setReturnUrl(route('subscriptions.complete'));
        $merchantPreferences->setCancelUrl(route('subscriptions.cancel'));
        $merchantPreferences->setAutoBillAmount('YES');
        $plan->setMerchantPreferences($merchantPreferences);

        try {
            $plan->create($this->api);
        } catch(PayPalConnectionException $e) {
            pd($e->getMessage());
            return $e->getMessage();
        }

        $package->paypal_id = $plan->id;
    }

    /**
     * @param Package $package
     */
    public function update(Package $package)
    {
        if($package->isDirty('active')) {
            $plan = $this->paypal->plan();
            $plan->setId($package->paypal_id);

            $patch = $this->paypal->patch()->setPath('/')->setOp('replace')->setValue([
                    'state' => ($package->active ? 'ACTIVE' : 'INACTIVE')
                ]);
            $patches = $this->paypal->patchrequest()->addPatch($patch);
            $plan->update($patches, $this->api);
        }
    }

    /**
     * @param Package $package
     */
    public function delete(Package $package)
    {
        $plan = $this->paypal->plan();
        $plan->setId($package->paypal_id);
        $plan->delete($this->api);
    }
}
