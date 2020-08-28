<?php namespace MissionControl\Payments\Providers;

use Illuminate\Support\ServiceProvider;
use MissionControl\Payments\Modules\Packages\Models\Package;
use MissionControl\Payments\Modules\Packages\Observers\PackageObserver;
use MissionControl\Payments\Modules\Currencies\Commands\LatestExchangeRates;
use MissionControl\Payments\Modules\Currencies\Models\Currency;
use MissionControl\Payments\Modules\Transactions\Models\Transaction;
use MissionControl\Payments\Modules\Transactions\Observers\TransactionObserver;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Class PaymentServiceProvider
 * @package MissionControl\Payments\Providers
 */
class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     * @return void
     */
    public function boot()
    {
        // publish
        $this->publishes([
            __DIR__ . '/../Modules/Packages/Config/packagecurrencies.php' => config_path('packagecurrencies.php'),
        ], 'config');

        // observers
        Package::observe(PackageObserver::class);
        Transaction::observe(TransactionObserver::class);

        if(!app()->runningInConsole()) {
            // currencies
            $currencies = (object)Currency::where('active', true)->get()->mapWithKeys(function($currency) {
                return [
                    $currency['code'] => [
                        'code' => $currency['code'],
                        'symbol' => $currency['symbol'],
                        'rate' => $currency['rate']
                    ]
                ];
            })->all();
            view()->share('currencies', $currencies);
            app()->singleton('currencies', function() use ($currencies) {
                return $currencies;
            });
        }

        if($this->app->runningInConsole()) {
            $this->commands([
                LatestExchangeRates::class,
            ]);
        }

        $this->app->booted(function() {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('exchange:rates')->daily();
        });
    }

    /**
     *
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Modules/Packages/Config/packagecurrencies.php', 'packagecurrencies');
    }

}
