<?php namespace MissionControl\Payments\Modules\PaymentMethods\Models;

use PayPal\Api\Address;
use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
use PayPal\Api\Amount;
use PayPal\Api\Currency;
use PayPal\Api\Details;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Links;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PaymentExecution;
use PayPal\Api\PaymentHistory;
use PayPal\Api\Plan;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\RelatedResources;
use PayPal\Api\Sale;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Api\Transactions;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Core\PPConfigManager;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

/**
 * Class Paypal
 * @package MissionControl\Payments\Modules\PaymentMethods\Models
 */
class Paypal
{
    /**
     * @return \PayPal\Api\Address
     */
    public function address()
    {
        return new Address;
    }

    /**
     * @return \PayPal\Api\Amount
     */
    public function amount()
    {
        return new Amount;
    }

    /**
     * @return \PayPal\Api\Details
     */
    public function details()
    {
        return new Details;
    }

    /**
     * @return \PayPal\Api\Authorization
     */
    public function authorization()
    {
        return new Authorization;
    }

    /**
     * @return \PayPal\Api\Capture
     */
    public function capture()
    {
        return new Capture;
    }

    /**
     * @return \PayPal\Api\CreditCard
     */
    public function creditCard()
    {
        return new CreditCard;
    }

    /**
     * @return \PayPal\Api\CreditCardToken
     */
    public function creditCardToken()
    {
        return new CreditCardToken;
    }

    public function currency()
    {
        return new Currency;
    }

    /**
     * @return \PayPal\Api\FundingInstrument
     */
    public function fundingInstrument()
    {
        return new FundingInstrument;
    }

    /**
     * @return \PayPal\Api\Item
     */
    public function item()
    {
        return new Item;
    }

    /**
     * @return \PayPal\Api\ItemList
     */
    public function itemList()
    {
        return new ItemList;
    }

    /**
     * @return \PayPal\Api\Links
     */
    public function links()
    {
        return new Links;
    }

    /**
     * @return \PayPal\Api\Payee
     */
    public function payee()
    {
        return new Payee;
    }

    /**
     * @return \PayPal\Api\Payer
     */
    public function payer()
    {
        return new Payer;
    }

    /**
     * @return \PayPal\Api\PayerInfo
     */
    public function payerInfo()
    {
        return new PayerInfo;
    }

    /**
     * @return \PayPal\Api\Payment
     */
    public function payment()
    {
        return new Payment;
    }

    /**
     * @return \PayPal\Api\PaymentExecution
     */
    public function paymentExecution()
    {
        return new PaymentExecution;
    }

    /**
     * @return \PayPal\Api\PaymentHistory
     */
    public function paymentHistory()
    {
        return new PaymentHistory;
    }

    /**
     * @return \PayPal\Api\RedirectUrls
     */
    public function redirectUrls()
    {
        return new RedirectUrls;
    }

    /**
     * @return \PayPal\Api\Refund
     */
    public function refund()
    {
        return new Refund;
    }

    /**
     * @return \PayPal\Api\RelatedResources
     */
    public function relatedResources()
    {
        return new RelatedResources;
    }

    /**
     * @return \PayPal\Api\Sale
     */
    public function sale()
    {
        return new Sale;
    }

    /**
     * @return \PayPal\Api\ShippingAddress
     */
    public function shippingAddress()
    {
        return new ShippingAddress;
    }

    /**
     * @return \PayPal\Api\Transactions
     */
    public function transactions()
    {
        return new Transactions;
    }

    /**
     * @return \PayPal\Api\Transaction
     */
    public function transaction()
    {
        return new Transaction;
    }

    /**
     * @return Plan
     */
    public function plan()
    {
        return new Plan;
    }

    /**
     * @return Agreement
     */
    public function agreement()
    {
        return new Agreement;
    }

    /**
     * @return AgreementStateDescriptor
     */
    public function agreementStateDescriptor()
    {
        return new AgreementStateDescriptor;
    }

    /**
     * @return PaymentDefinition
     */
    public function paymentDefinition()
    {
        return new PaymentDefinition;
    }

    /**
     * @return MerchantPreferences
     */
    public function merchantPreferences()
    {
        return new MerchantPreferences;
    }

    /**
     * @return PatchRequest
     */
    public function patchrequest()
    {
        return new PatchRequest;
    }

    /**
     * @return Patch
     */
    public function patch()
    {
        return new Patch;
    }

    /**
     * @return VerifyWebhookSignature
     */
    public function verifywebhooksignature()
    {
        return new VerifyWebhookSignature;
    }

    /**
     * @param null $clientId
     * @param null $clientSecret
     * @param null $requestId
     * @return \PayPal\Rest\ApiContext
     */
    public function apiContext($clientId = null, $clientSecret = null, $requestId = null)
    {
        $credentials = self::OAuthTokenCredential($clientId, $clientSecret);

        return new ApiContext($credentials, $requestId);
    }

    /**
     * @param null $ClientId
     * @param null $ClientSecret
     * @return PayPal/Auth/OAuthTokenCredential
     */
    public static function OAuthTokenCredential($ClientId = null, $ClientSecret = null)
    {
        //define("PP_CONFIG_PATH", __DIR__);

        if(isset($ClientId) && isset($ClientSecret)) {
            return new OAuthTokenCredential($ClientId, $ClientSecret);
        }

        $configManager = PPConfigManager::getInstance();
        // $cred is used by samples that include this bootstrap file
        // This piece of code simply demonstrates how you can
        // dynamically pass in a client id/secret instead of using
        // the config file. If you do not need a way to pass
        // in credentials dynamically, you can skip the
        // <Resource>::setCredential($cred) calls that
        // you see in the samples.

        $cred = new OAuthTokenCredential($configManager->get('acct1.ClientId'), $configManager->get('acct1.ClientSecret'));

        return $cred;
    }

    /**
     * Get the base URL
     * @return mixed
     */
    public function getBaseUrl()
    {
        return url('/');
    }

    /**
     * grape payment details using the paymentId
     * @param $paymentId
     * @param null $apiContext
     * @return \PayPal\Api\Payment
     */
    public static function getById($paymentId, $apiContext = null)
    {
        if(isset($apiContext)) {
            return Payment::get($paymentId, $apiContext);
        }
        return Payment::get($paymentId);
    }

    /**
     * grape all payment details
     * @param $param
     * @param null $apiContext
     * @return \PayPal\Api\Payment
     */
    public static function getAll($param, $apiContext = null)
    {
        if(isset($apiContext)) {
            return Payment::all($param, $apiContext);
        }
        return Payment::all($param);
    }

}