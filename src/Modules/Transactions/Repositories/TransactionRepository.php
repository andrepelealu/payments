<?php namespace MissionControl\Payments\Modules\Transactions\Repositories;

use Eyeweb\MissionControl\EloquentRepository;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;

/**
 * Class TransactionRepository
 * @package MissionControl\Payments\Modules\Transactions\Repositories
 */
class TransactionRepository extends EloquentRepository implements TransactionInterface
{
    /**
     * @var Transaction
     */
    private $model;

    /**
     * @param Transaction $model
     */
    function __construct(Transaction $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }
}
