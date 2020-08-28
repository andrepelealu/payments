<?php namespace MissionControl\Payments\Modules\Packages\Repositories;

use Eyeweb\MissionControl\EloquentInterface;

/**
 * Interface PackageInterface
 * @package MissionControl\Payments\Modules\Packages\Repositories
 */
interface PackageInterface extends EloquentInterface
{
    public function getActive();
}
