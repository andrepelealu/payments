<?php namespace MissionControl\Payments\Modules\Packages\Repositories;

use Eyeweb\MissionControl\EloquentRepository;
use MissionControl\Payments\Modules\Packages\Models\Package;

/**
 * Class PackageRepository
 * @package MissionControl\Payments\Modules\Packages\Repositories
 */
class PackageRepository extends EloquentRepository implements PackageInterface
{
    /**
     * @var Package
     */
    private $model;

    /**
     * PackageRepository constructor.
     * @param Package $model
     */
    function __construct(Package $model)
    {
        parent::__construct($model);

        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->model->where('active', true)->orderBy('sort_order', 'asc')->get();
    }

    /**
     * @return mixed
     */
    public function getActiveAndSubscribed()
    {
        return $this->model->where('active', true)
            ->orWhere('active', false)
            ->where('id', auth()->user()->subscription_package_id)
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
