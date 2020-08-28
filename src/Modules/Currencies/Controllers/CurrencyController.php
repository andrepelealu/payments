<?php namespace MissionControl\Payments\Modules\Currencies\Controllers;

use App\Http\Controllers\Controller;
use http\Cookie;
use Illuminate\Support\Facades\Request;

/**
 * Class CurrencyController
 * @package MissionControl\Payments\Modules\Currencies\Controllers
 */
class CurrencyController extends Controller
{
    /**
     * @param \Illuminate\Support\Facades\Request $request
     * @param                                     $code
     * @return \Illuminate\Http\RedirectResp
     */
    public function changeCurrency(Request $request, $code)
    {
        if(array_key_exists($code, app('currencies'))) {
            return back()->withCookie('currency', $code);
        }
        return back();
    }
}
