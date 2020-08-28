<?php namespace MissionControl\Payments\Modules\Transactions\Observers;

use MissionControl\Payments\Modules\Transactions\Models\Transaction;

/**
 * Class TransactionObserver
 * @package MissionControl\Payments\Modules\Transactions\Observers
 */
class TransactionObserver
{
    /**
     * @param Transaction $transaction
     */
    public function created(Transaction $transaction)
    {
        $this->updateModelStatus($transaction);
    }

    /**
     * @param Transaction $transaction
     */
    public function updated(Transaction $transaction)
    {
        $this->updateModelStatus($transaction);
    }

    /**
     * Update the status of the type model
     * @param $transaction
     * @return mixed
     */
    public function updateModelStatus($transaction)
    {
        $model = (new $transaction->type)->where('id', $transaction->type_id)->first();

        switch($transaction->status) {
            case "complete":
                $model->status = 'paid';
                break;
            case "failed":
                $model->status = 'cancelled';
                break;
            case "refunded":
                $model->status = 'refunded';
                break;
        }

        return $model->save();
    }
}
