<?php namespace MissionControl\Payments\Modules\PaymentMethods\Models;

use Stripe\Account;
use Stripe\AccountLink;
use Stripe\ApiRequestor;
use Stripe\ApiResponse;
use Stripe\ApplePayDomain;
use Stripe\ApplicationFee;
use Stripe\ApplicationFeeRefund;
use Stripe\Balance;
use Stripe\BalanceTransaction;
use Stripe\BankAccount;
use Stripe\Card;
use Stripe\Charge;
use Stripe\CheckoutSession;
use Stripe\Collection;
use Stripe\CountrySpec;
use Stripe\Coupon;
use Stripe\Customer;
use Stripe\Discount;
use Stripe\Dispute;
use Stripe\EphemeralKey;
use Stripe\Event;
use Stripe\ExchangeRate;
use Stripe\File;
use Stripe\FileLink;
use Stripe\FileUpload;
use Stripe\Invoice;
use Stripe\InvoiceItem;
use Stripe\InvoiceLineItem;
use Stripe\IssuerFraudRecord;
use Stripe\LoginLink;
use Stripe\Order;
use Stripe\OrderItem;
use Stripe\OrderReturn;
use Stripe\PaymentIntent;
use Stripe\Payout;
use Stripe\Person;
use Stripe\Plan;
use Stripe\Product;
use Stripe\Recipient;
use Stripe\RecipientTransfer;
use Stripe\Refund;
use Stripe\RequestTelemetry;
use Stripe\Review;
use Stripe\SKU;
use Stripe\Source;
use Stripe\SourceTransaction;
use Stripe\StripeObject;
use Stripe\Subscription;
use Stripe\SubscriptionItem;
use Stripe\ThreeDSecure;
use Stripe\Token;
use Stripe\Topup;
use Stripe\Transfer;
use Stripe\TransferReversal;
use Stripe\UsageRecord;
use Stripe\UsageRecordSummary;
use Stripe\WebhookEndpoint;

/**
 * Class Stripe
 * @package MissionControl\Payments\Modules\PaymentMethods\Models
 */
class Stripe
{
    /**
     * @return Account
     */
    public function account()
    {
        return new Account();
    }

    /**
     * @return AccountLink
     */
    public function accountLink()
    {
        return new AccountLink();
    }

    /**
     * @return ApiRequestor
     */
    public function apiRequestor()
    {
        return new ApiRequestor();
    }

    /**
     * @return ApiResponse
     */
    public function apiResponse()
    {
        return new ApiResponse();
    }

    /**
     * @return ApplePayDomain
     */
    public function applePayDomain()
    {
        return new ApplePayDomain();
    }

    /**
     * @return ApplicationFee
     */
    public function applicationFee()
    {
        return new ApplicationFee();
    }

    /**
     * @return ApplicationFeeRefund
     */
    public function applicationFeeRefund()
    {
        return new ApplicationFeeRefund();
    }

    /**
     * @return Balance
     */
    public function balance()
    {
        return new Balance();
    }

    /**
     * @return BalanceTransaction
     */
    public function balanceTransaction()
    {
        return new BalanceTransaction();
    }

    /**
     * @return BankAccount
     */
    public function bankAccount()
    {
        return new BankAccount();
    }

    /**
     * @return Card
     */
    public function card()
    {
        return new Card();
    }

    /**
     * @return Charge
     */
    public function charge()
    {
        return new Charge();
    }

    /**
     * @return CheckoutSession
     */
    public function checkoutSession()
    {
        return new CheckoutSession();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection();
    }

    /**
     * @return CountrySpec
     */
    public function countrySpec()
    {
        return new CountrySpec();
    }

    /**
     * @return Coupon
     */
    public function coupon()
    {
        return new Coupon();
    }

    /**
     * @return Customer
     */
    public function customer()
    {
        return new Customer();
    }

    /**
     * @return Discount
     */
    public function discount()
    {
        return new Discount();
    }

    /**
     * @return Dispute
     */
    public function dispute()
    {
        return new Dispute();
    }

    /**
     * @return EphemeralKey
     */
    public function ephemeralKey()
    {
        return new EphemeralKey();
    }

    /**
     * @return Event
     */
    public function event()
    {
        return new Event();
    }

    /**
     * @return ExchangeRate
     */
    public function exchangeRate()
    {
        return new ExchangeRate();
    }

    /**
     * @return File
     */
    public function file()
    {
        return new File();
    }

    /**
     * @return FileLink
     */
    public function fileLink()
    {
        return new FileLink();
    }

    /**
     * @return FileUpload
     */
    public function fileUpload()
    {
        return new FileUpload();
    }

    /**
     * @return Invoice
     */
    public function invoice()
    {
        return new Invoice();
    }

    /**
     * @return InvoiceItem
     */
    public function invoiceItem()
    {
        return new InvoiceItem();
    }

    /**
     * @return InvoiceLineItem
     */
    public function invoiceLineItem()
    {
        return new InvoiceLineItem();
    }

    /**
     * @return IssuerFraudRecord
     */
    public function issuerFraudRecord()
    {
        return new IssuerFraudRecord();
    }

    /**
     * @return LoginLink
     */
    public function loginLink()
    {
        return new LoginLink();
    }

    /**
     * @return Order
     */
    public function order()
    {
        return new Order();
    }

    /**
     * @return OrderItem
     */
    public function orderItem()
    {
        return new OrderItem();
    }

    /**
     * @return OrderReturn
     */
    public function orderReturn()
    {
        return new OrderReturn();
    }

    /**
     * @return PaymentIntent
     */
    public function paymentIntent()
    {
        return new PaymentIntent();
    }

    /**
     * @return Payout
     */
    public function payout()
    {
        return new Payout();
    }

    /**
     * @return Person
     */
    public function person()
    {
        return new Person();
    }

    /**
     * @return Plan
     */
    public function plan()
    {
        return new Plan();
    }

    /**
     * @return Product
     */
    public function product()
    {
        return new Product();
    }

    /**
     * @return Recipient
     */
    public function recipient()
    {
        return new Recipient();
    }

    /**
     * @return RecipientTransfer
     */
    public function recipientTransfer()
    {
        return new RecipientTransfer();
    }

    /**
     * @return Refund
     */
    public function refund()
    {
        return new Refund();
    }

    /**
     * @return RequestTelemetry
     */
    public function requestTelemetry()
    {
        return new RequestTelemetry();
    }

    /**
     * @return Review
     */
    public function review()
    {
        return new Review();
    }

    /**
     * @return SKU
     */
    public function sku()
    {
        return new SKU();
    }

    /**
     * @return Source
     */
    public function source()
    {
        return new Source();
    }

    /**
     * @return SourceTransaction
     */
    public function sourceTransaction()
    {
        return new SourceTransaction();
    }

    /**
     * @return \Stripe\Stripe
     */
    public function stripe()
    {
        return new \Stripe\Stripe();
    }

    /**
     * @return StripeObject
     */
    public function stripeObject()
    {
        return new StripeObject();
    }

    /**
     * @return Subscription
     */
    public function subscription()
    {
        return new Subscription();
    }

    /**
     * @return SubscriptionItem
     */
    public function subscriptionItem()
    {
        return new SubscriptionItem();
    }

    /**
     * @return ThreeDSecure
     */
    public function threeDSecure()
    {
        return new ThreeDSecure();
    }

    /**
     * @return Token
     */
    public function token()
    {
        return new Token();
    }

    /**
     * @return Topup
     */
    public function topUp()
    {
        return new Topup();
    }

    /**
     * @return Transfer
     */
    public function transfer()
    {
        return new Transfer();
    }

    /**
     * @return TransferReversal
     */
    public function transferReversal()
    {
        return new TransferReversal();
    }

    /**
     * @return UsageRecord
     */
    public function usageRecord()
    {
        return new UsageRecord();
    }

    /**
     * @return UsageRecordSummary
     */
    public function usageRecordSummary()
    {
        return new UsageRecordSummary();
    }

    /**
     * @return WebhookEndpoint
     */
    public function webhookEndpoint()
    {
        return new WebhookEndpoint();
    }
}

