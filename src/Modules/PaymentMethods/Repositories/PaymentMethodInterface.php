<?php namespace MissionControl\Payments\Modules\PaymentMethods\Repositories;

use Eyeweb\MissionControl\EloquentInterface;

/**
 * Interface PaymentMethodInterface
 * @package MissionControl\Payments\Modules\Packages\Repositories
 */
interface PaymentMethodInterface extends EloquentInterface
{
    /**
     * @return mixed
     */
    public function getActive();
}
