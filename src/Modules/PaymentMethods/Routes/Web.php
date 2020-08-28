<?php

Route::group(['prefix' => 'mc-admin', 'as' => 'mc-admin.', 'middleware' => ['auth.admin', 'auth.permissions']], function ($router) {
	Route::resource('paymentmethods', '\MissionControl\Payments\Modules\PaymentMethods\Controllers\AdminPaymentMethodController', ['only' => ['index', 'edit', 'update']])->parameters(['paymentmethods' => 'paymentmethod']);
});