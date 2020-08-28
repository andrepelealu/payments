<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use Carbon\Carbon;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;
use MissionControl\Payments\Modules\Transactions\Models\Transaction as PaymentTransaction;
use Modules\Users\Models\User;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;

/**
 * Class AmazonPaymentController
 * @package MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class AmazonPaymentController
{
    /**
     * @var paymentMethod
     */
    private $paymentMethod;

    /**
     * PaypalPaymentController constructor.
     * @param $paymentMethod
     */
    public function __construct($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @param $object
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay($object, $token = false)
    {

    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function capture($transaction)
    {

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
