<?php namespace MissionControl\Payments\Modules\Packages\Controllers;

use App\Http\Controllers\Controller;
use MissionControl\Payments\Modules\Packages\Models\Package;
use MissionControl\Payments\Modules\Packages\Repositories\PackageRepository;
use MissionControl\Payments\Modules\Packages\Requests\CreatePackageRequest;
use MissionControl\Payments\Modules\Packages\Requests\PackageRequest;
use MissionControl\Payments\Modules\Packages\Requests\UpdatePackageRequest;

/**
 * Class AdminPackageController
 * @package MissionControl\Payments\Modules\Packages\Controllers
 */
class AdminPackageController extends Controller
{
    /**
     * @var PackagesRepo
     */
    private $packageRepo;

    /**
     * AdminPackageController constructor.
     * @param PackageRepository $packageRepo
     */
    function __construct(PackageRepository $packageRepo)
    {
        $this->packageRepo = $packageRepo;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index()
    {
        $filter = request()->has('filter') ? request()->input('filter') : false;

        $packages = $this->packageRepo->getAllFiltered($filter, false, 'sort_order', 'asc');

        return view('Packages::Admin.index', compact('packages', 'filter'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create()
    {
        $currencies = [];
        foreach(config('packagecurrencies') as $code => $currency) {
            $currencies[$code] = $code . ' - ' . $currency['name'];
        }

        return view('Packages::Admin.create', compact('currencies'));
    }

    /**
     * @param CreatePackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePackageRequest $request)
    {
        if($package = $this->packageRepo->create($request->input())) {
            return redirect()
                ->route('mc-admin.packages.edit', ['packages' => $package->id])
                ->with('flash_message', 'The package was added successfully.')
                ->with('flash_message_type', 'success');
        }
    }

    /**
     * @param Package $package
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function edit(Package $package)
    {
        $currencies = [];
        foreach(config('packagecurrencies') as $code => $currency) {
            $currencies[$code] = $code . ' - ' . $currency['name'];
        }

        return view('Packages::Admin.edit', compact('package', 'currencies'));
    }

    /**
     * @param Package $package
     * @param UpdatePackageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Package $package, UpdatePackageRequest $request)
    {
        if($this->packageRepo->update($package->id, $request->input())) {
            return back()
                ->with('flash_message', 'The package update was completed successfully .')
                ->with('flash_message_type', 'success');
        }
    }

    /**
     * @param Package $package
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Package $package)
    {
        if($this->packageRepo->delete($package->id, false)) {
            return redirect()
                ->route('mc-admin.packages.index')
                ->with('flash_message', 'The package was deleted')
                ->with('flash_message_type', 'success');
        }
        return back();
    }

    /**
     * @param Package $package
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function confirmDelete(Package $package)
    {
        $destroyRoute = route('mc-admin.packages.destroy', $package->id);
        return view('Admins::Admin.partials.confirm-delete', compact('destroyRoute'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        if($this->packageRepo->restore($id)) {
            return redirect()
                ->route('mc-admin.packages.index')
                ->with('flash_message', 'The package was restored')
                ->with('flash_message_type', 'success');
        }
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function confirmRestore($id)
    {
        $package = $this->packageRepo->getById($id);
        $restoreRoute = route('mc-admin.packages.restore', $package->id);
        return view('Admins::Admin.partials.confirm-restore', compact('restoreRoute'));
    }
}
