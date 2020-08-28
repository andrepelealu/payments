<?php namespace MissionControl\Payments\Modules\Packages\Controllers;

use App\Http\Controllers\Controller;
use MissionControl\Payments\Modules\Packages\Repositories\PackageRepository;
use MissionControl\Payments\Modules\Packages\Requests\SubscribeRequest;
use MissionControl\Payments\Modules\PaymentMethods\Repositories\PaymentMethodRepository;

/**
 * Class PackageController
 * @package MissionControl\Payments\Modules\Packages\Controllers
 */
class PackageController extends Controller
{
    /**
     * @var PackageRepository
     */
    private $packageRepo;
    /**
     * @var PaymentMethodRepository
     */
    private $paymentmethodRepo;

    /**
     * AdminPackageController constructor.
     * @param PackageRepository $packageRepo
     * @param PaymentMethodRepository $paymentmethodRepo
     */
    function __construct(PackageRepository $packageRepo, PaymentMethodRepository $paymentmethodRepo)
    {
        $this->packageRepo = $packageRepo;
        $this->paymentmethodRepo = $paymentmethodRepo;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index()
    {
        $packages = $this->packageRepo->getActiveAndSubscribed();

        return view('Packages::Frontend.index', compact('packages'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function show($id)
    {
        $package = $this->packageRepo->getById($id);
        $paymentMethods = $this->paymentmethodRepo->getActive();

        return view('Packages::Frontend.show', compact('package', 'paymentMethods'));
    }

    /**
     * @param SubscribeRequest $request
     * @param $id
     * @return mixed
     */
    public function subscribe(SubscribeRequest $request, $id)
    {
        $package = $this->packageRepo->getById($id);
        $paymentMethod = $this->paymentmethodRepo->getById($request->input('paymentmethod_id'));

        $controller = new $paymentMethod->subscription_controller($paymentMethod);

        return $controller->subscribe($package);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function upgrade($id)
    {
        $package = $this->packageRepo->getById($id);
        $paymentMethod = $this->paymentmethodRepo->getById(auth()->user()->subscription_method_id);

        $controller = new $paymentMethod->subscription_controller($paymentMethod);

        return $controller->upgrade($package);
    }

    /**
     * @return mixed
     */
    public function complete()
    {
        $paymentMethod = $this->paymentmethodRepo->getById(auth()->user()->subscription_method_id);
        $controller = new $paymentMethod->subscription_controller($paymentMethod);

        return $controller->complete(request()->input());
    }

    /**
     * @return mixed
     */
    public function cancel()
    {
        $paymentMethod = $this->paymentmethodRepo->getById(auth()->user()->subscription_method_id);
        $controller = new $paymentMethod->subscription_controller($paymentMethod);

        return $controller->cancel();
    }

    /**
     * @param $paymentmethod
     * @return mixed
     */
    public function webhooks($paymentmethod)
    {
        $paymentMethod = $this->paymentmethodRepo->getBy(['name' => $paymentmethod]);
        $controller = new $paymentMethod->subscription_controller($paymentMethod);

        return $controller->webhooks(request());
    }
}
