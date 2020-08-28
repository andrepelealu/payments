<?php namespace MissionControl\Payments\Modules\PaymentMethods\Repositories;

use Eyeweb\MissionControl\EloquentRepository;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;

/**
 * Class PaymentMethodRepository
 * @package MissionControl\Payments\Modules\PaymentMethods\Repositories
 */
class PaymentMethodRepository extends EloquentRepository implements PaymentMethodInterface
{
    /**
     * @var PaymentMethod
     */
    private $model;

    /**
     * PaymentMethodRepository constructor.
     * @param PaymentMethod $model
     */
    function __construct(PaymentMethod $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->model->where('active', true)->get();
    }
}
