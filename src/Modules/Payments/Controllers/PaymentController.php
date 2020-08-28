<?php namespace MissionControl\Payments\Modules\Payments\Controllers;

use MissionControl\Payments\Modules\PaymentMethods\Repositories\PaymentMethodRepository;
use MissionControl\Payments\Modules\Transactions\Repositories\TransactionRepository;

/**
 * Class PaymentController
 * @package MissionControl\Payments\Modules\Payments\Controllers
 */
class PaymentController
{
    /**
     * @var PaymentMethodRepository
     */
    private $paymentMethodRepo;

    /**
     * @var TransactionRepository
     */
    private $transactionRepo;

    /**
     * PaymentController constructor.
     * @param PaymentMethodRepository $paymentMethodRepo
     * @param TransactionRepository $transactionRepo
     */
    function __construct(PaymentMethodRepository $paymentMethodRepo, TransactionRepository $transactionRepo)
    {
        $this->paymentMethodRepo = $paymentMethodRepo;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * @param $vendor_reference
     * @return mixed
     */
    public function capture($vendor_reference)
    {
        if($transaction = $this->transactionRepo->getBy(['vendor_reference' => $vendor_reference, 'status' => 'pending', 'user_id' => (auth()->check() ? auth()->user()->id : null)], ['paymentMethod'])) {
            $controller = new $transaction->paymentMethod->payment_controller($transaction->paymentMethod);
            return $controller->capture($transaction);
        }
        abort(404);
    }

    /**
     * @param $reference
     * @return mixed
     */
    public function complete($reference)
    {
        if($transaction = $this->transactionRepo->getBy(['reference' => $reference, 'status' => 'complete', 'user_id' => (auth()->check() ? auth()->user()->id : null)], ['paymentMethod'])) {
            $controller = new $transaction->paymentMethod->payment_controller($transaction->paymentMethod);
            return $controller->complete($transaction);
        }
        abort(404);
    }

    /**
     * @param $reference
     * @return mixed
     */
    public function failed($reference)
    {
        if($transaction = $this->transactionRepo->getBy(['reference' => $reference, 'status' => 'failed', 'user_id' => (auth()->check() ? auth()->user()->id : null)], ['paymentMethod'])) {
            $controller = new $transaction->paymentMethod->payment_controller($transaction->paymentMethod);
            return $controller->failed($transaction);
        }
        abort(404);
    }

    /**
     * @param $type
     * @return mixed
     */
    public function webhooks($type)
    {
        if ($paymentMethod = $this->paymentMethodRepo->getBy(['name' => $type])) {
            $controller = new $paymentMethod->payment_controller($paymentMethod);
            return $controller->webhooks();
        }
        abort(404);
    }
}
