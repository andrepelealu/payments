<?php

Route::group(['prefix' => 'mc-admin', 'as' => 'mc-admin.', 'middleware' => ['auth.admin', 'auth.permissions']], function ($router) {
	Route::get('packages/{package}/confirm-delete', ['as' => 'packages.confirm-delete', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\AdminPackageController@confirmDelete']);
	Route::get('packages/{id}/confirm-restore', ['as' => 'packages.confirm-restore', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\AdminPackageController@confirmRestore']);
	Route::post('packages/{id}/restore', ['as' => 'packages.restore', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\AdminPackageController@restore']);
	Route::resource('packages', '\MissionControl\Payments\Modules\Packages\Controllers\AdminPackageController', ['except' => ['show']])->parameters(['packages' => 'package']);
});

Route::group(['middleware' => 'auth.user'], function() {
    Route::get('subscriptions', ['as' => 'subscriptions.index', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@index']);
    Route::get('subscriptions/complete', ['as' => 'subscriptions.complete', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@complete']);
    Route::get('subscriptions/cancel', ['as' => 'subscriptions.cancel', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@cancel']);
    Route::get('subscriptions/upgrade/{package_id}', ['as' => 'subscriptions.upgrade', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@upgrade']);
    Route::get('subscriptions/{package_id}', ['as' => 'subscriptions.package', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@show']);
    Route::post('subscriptions/{package_id}', ['as' => 'subscriptions.package.post', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@subscribe']);
});

Route::group(['before' => 'csrf', 'prefix' => 'webhooks', 'as' => 'webhooks.'], function() {
    Route::post('subscriptions/{paymentmethod}', ['as' => 'subscriptions.webhooks', 'uses' => '\MissionControl\Payments\Modules\Packages\Controllers\PackageController@webhooks']);
});