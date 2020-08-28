<?php namespace MissionControl\Payments\Modules\Packages\Observers;

use MissionControl\Payments\Modules\Packages\Models\Package;
use MissionControl\Payments\Modules\Packages\Repositories\PackageRepository;
use MissionControl\Payments\Modules\PaymentMethods\Repositories\PaymentMethodRepository;

/**
 * Class PackageObserver
 * @package MissionControl\Payments\Modules\Packages\Observers
 */
class PackageObserver
{
    /**
     * @var PaymentMethodRepository
     */
    private $paymentmethodRepo;

    /**
     * PackageObserver constructor.
     * @param PaymentMethodRepository $paymentmethodRepo
     */
    public function __construct(PaymentMethodRepository $paymentmethodRepo)
    {
        $this->paymentmethodRepo = $paymentmethodRepo;
    }

    /**
     * @param Package $package
     */
    public function creating(Package $package)
    {
        $paymentMethods = $this->paymentmethodRepo->getActive();
        if($paymentMethods->count() > 0) {
            foreach($paymentMethods as $paymentMethod) {
                $paypalPackage = new $paymentMethod->model($paymentMethod);
                $paypalPackage->create($package);
            }
        }
    }

    /**
     * @param Package $package
     */
    public function updating(Package $package)
    {
        $paymentMethods = $this->paymentmethodRepo->getActive();
        if($paymentMethods->count() > 0) {
            foreach($paymentMethods as $paymentMethod) {
                $paypalPackage = new $paymentMethod->model($paymentMethod);
                $paypalPackage->update($package);
            }
        }
    }

    /**
     * @param Package $package
     */
    public function deleting(Package $package)
    {
        $paymentMethods = $this->paymentmethodRepo->getActive();
        if($paymentMethods->count() > 0) {
            foreach($paymentMethods as $paymentMethod) {
                $paypalPackage = new $paymentMethod->model($paymentMethod);
                $paypalPackage->delete($package);
            }
        }
    }

}