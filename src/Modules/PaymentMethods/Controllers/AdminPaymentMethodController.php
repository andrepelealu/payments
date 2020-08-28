<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use App\Http\Controllers\Controller;
use MissionControl\Payments\Modules\PaymentMethods\Models\PaymentMethod;
use MissionControl\Payments\Modules\PaymentMethods\Repositories\PaymentMethodRepository;
use MissionControl\Payments\Modules\PaymentMethods\Requests\PaymentMethodRequest;


/**
 * Class AdminPaymentMethodController
 * @paymentmethod MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class AdminPaymentMethodController extends Controller {

    /**
     * @var PaymentMethodRepository
     */
    private $paymentmethodRepo;

    /**
     * AdminPaymentMethodController constructor.
     * @param PaymentMethodRepository $paymentmethodRepo
     */
    function __construct(PaymentMethodRepository $paymentmethodRepo)
    {
        $this->paymentmethodRepo = $paymentmethodRepo;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index()
    {
        $paymentmethods = $this->paymentmethodRepo->getAll(false, 'id', 'asc');

        return view('PaymentMethods::Admin.index', compact('paymentmethods'));
    }

    /**
     * @param PaymentMethod $paymentmethod
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function edit(PaymentMethod $paymentmethod)
    {
        return view('PaymentMethods::Admin.edit', compact('paymentmethod'));
    }

    /**
     * @param PaymentMethod $paymentmethod
     * @param PaymentMethodRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PaymentMethod $paymentmethod, PaymentMethodRequest $request)
    {
        if ( $this->paymentmethodRepo->update($paymentmethod->id, $request->input())){
            return back()
                ->with('flash_message', 'The payment method update was completed successfully .')
                ->with('flash_message_type', 'success');
        }
    }
}
