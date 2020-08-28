<?php

Route::get('currency/{code}', ['as' => 'currency.change', 'uses' => '\MissionControl\Payments\Modules\Currencies\Controllers\CurrencyController@changeCurrency']);
