<?php

Route::get('payment/{vendor_reference}/capture', ['as' => 'payment.capture', 'uses' => '\MissionControl\Payments\Modules\Payments\Controllers\PaymentController@capture']);
Route::get('payment/{reference}/complete', ['as' => 'payment.complete', 'uses' => '\MissionControl\Payments\Modules\Payments\Controllers\PaymentController@complete']);
Route::get('payment/{reference}/failed', ['as' => 'payment.failed', 'uses' => '\MissionControl\Payments\Modules\Payments\Controllers\PaymentController@failed']);

Route::group(['before' => 'csrf', 'prefix' => 'webhooks', 'as' => 'webhooks.'], function() {
    Route::post('payment/{paymentMethod}', ['as' => 'payment.webhooks', 'uses' => '\MissionControl\Payments\Modules\Payments\Controllers\PaymentController@webhooks']);
});
