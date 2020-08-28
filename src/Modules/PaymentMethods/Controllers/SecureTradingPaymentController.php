<?php namespace MissionControl\Payments\Modules\PaymentMethods\Controllers;

use MissionControl\Payments\Modules\Transactions\Models\Transaction as PaymentTransaction;
use Modules\Users\Models\User;
use Log;

/**
 * Class SecureTradingPaymentController
 * @package MissionControl\Payments\Modules\PaymentMethods\Controllers
 */
class SecureTradingPaymentController
{
	/**
	 * @var
	 */
	private $paymentMethod;
	
	/**
	 * @var
	 */
	private $model;
	
	/**
	 * SecureTradingPaymentController constructor.
	 * @param $paymentMethod
	 */
	public function __construct($paymentMethod)
	{
		$this->paymentMethod = $paymentMethod;
		$this->api = \Securetrading\api([
			'username' => $this->paymentMethod->details->username,
			'password' => $this->paymentMethod->details->password
		]);
	}
	
	/**
	 * @param $object
	 * @param bool $token
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function pay($object, $token = false)
	{
		// create transaction
		$transaction = $object->transactions()->orderBy('id', 'desc')->first();
		
		// start building request
		$st_request = [
			'sitereference' => $this->paymentMethod->details->sitereference,
			'requesttypedescriptions' => array('AUTH'),
			'accounttypedescription' => 'ECOM',
			'currencyiso3a' => $object->currency,
			'baseamount' => '' . ($object->total * 100) . '',
			'orderreference' => $object->reference,
		];
		
		// check if a third party wallet is being using i.e. Apple Pay
		if (request()->has('request.0.wallettoken') && request()->has('request.0.walletsource')) {
			$st_request['wallettoken'] = request()->input('request.0.wallettoken');
			$st_request['walletsource'] = request()->input('request.0.walletsource');
		} else {
			// standard card input token
			$st_request['cachetoken'] = $token;
		}
		
		// try process the request
		$response = $this->api->process($st_request);
		
		$response = $response->toArray()['responses'][0];
		
		// all good
		if (in_array($response['errorcode'], [0])) {
			
			// set vendor reference
			$transaction->vendor_reference = $response['transactionreference'];
			
			// check the settle status
			if (in_array($response['settlestatus'], [0, 1, 10, 100])) {
				
				// check if the amounts and reference match up
				if ((int)$response['baseamount'] === (int)($object->total * 100) && $response['orderreference'] == $object->reference) {
					// all good we are going to receive funds
					$transaction->status = 'complete';
					$transaction->error_code = $response['errorcode'];
					$transaction->error_message = $response['errormessage'];
				} else {
					// someones messing - 1337
					$transaction->status = 'failed';
					$transaction->error_code = 123;
					$transaction->error_message = 'Values have been manipulated.';
				}
			}
			
			// transaction suspended
			if (in_array($response['settlestatus'], [2, 3])) {
				$transaction->status = 'failed';
				$transaction->error_code = $response['errorcode'];
				$transaction->error_message = $response['errormessage'];
				Log::emergency('TRANSACTION ID: ' . $transaction->id . ' - CODE: ' . $response['errorcode'] . ' MESSAGE: ' . $response['errormessage']);
			}
		}
		
		// invalid token
		if (in_array($response['errorcode'], [20030])) {
			$transaction->status = 'failed';
			$transaction->error_code = $response['errorcode'];
			$transaction->error_message = $response['errormessage'];
			Log::emergency('TRANSACTION ID: ' . $transaction->id . ' - CODE: ' . $response['errorcode'] . ' MESSAGE: ' . $response['errormessage']);
		}
		
		// invalid data from user
		if (in_array($response['errorcode'], [30000])) {
			$transaction->status = 'failed';
			$transaction->error_code = $response['errorcode'];
			$transaction->error_message = $response['errormessage'];
			Log::emergency('TRANSACTION ID: ' . $transaction->id . ' - CODE: ' . $response['errorcode'] . ' MESSAGE: ' . $response['errormessage']);
		}
		
		// system error with us or secure trading
		if (in_array($response['errorcode'], [70000, 60010, 60034, 99999])) {
			$transaction->status = 'failed';
			$transaction->error_code = $response['errorcode'];
			$transaction->error_message = $response['errormessage'];
			Log::emergency('TRANSACTION ID: ' . $transaction->id . ' - CODE: ' . $response['errorcode'] . ' MESSAGE: ' . $response['errormessage']);
		}
		
		// no account found (they are trying to use amex but its not active).
		if (in_array($response['errorcode'], [40000])) {
			$transaction->status = 'failed';
			$transaction->error_code = $response['errorcode'];
			$transaction->error_message = 'The card used is not supported by our payment gateway, please use another card';
			Log::emergency('TRANSACTION ID: ' . $transaction->id . ' - CODE: ' . $response['errorcode'] . ' MESSAGE: ' . $response['errormessage']);
		}
		
		// save the transaction
		$transaction->save();
		
		// redirect complete
		if ($transaction->status == 'complete') {
			if (isset($response['walletsource'])) {
				return response()->json([
					'response' => [[
									   'errorcode' => $response['errorcode'],
									   'errormessage' => $response['errormessage']
								   ]]
				]);
			}
			return redirect()->route('payment.complete', $transaction->reference);
		}
		
		// if for some reason the transaction has made it this far without being set as failed
		// this acts as a catch all and will mark the transaction as failed.
		// so many things to check for with secure trading this is needed
		if($transaction->status != 'failed') {
			$transaction->status = 'failed';
			$transaction->error_code =  $response['errorcode'];
			$transaction->error_message = $response['errormessage'];
			$transaction->save();
		}
		// redirect failed
		return redirect()->route('payment.failed', $transaction->reference);
	}
	
	/**
	 * @param $transaction
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function complete($transaction)
	{
		$parentObject = (new $transaction->type)->find($transaction->type_id);
		return view('Payments::Frontend.payment-complete', compact('transaction', 'parentObject'));
	}
	
	/**
	 * @param $transaction
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|string|null
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 */
	public function failed($transaction)
	{
		$parentObject = (new $transaction->type)->find($transaction->type_id);
		return view('Payments::Frontend.payment-failed', compact('transaction', 'parentObject'));
	}
}