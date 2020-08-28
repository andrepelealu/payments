<?php namespace MissionControl\Payments\Modules\Currencies\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use MissionControl\Payments\Modules\Currencies\Models\Currency;
use NumberFormatter;

/**
 * Class LatestExchangeRates
 * @package MissionControl\Payments\Modules\Currencies\Commands
 */
class LatestExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'exchange:rates';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Get the latest exchange rates based on GBP.';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {
            $client = new Client();
            $response = $client->get('https://api.exchangeratesapi.io/latest?base=GBP');
            $response = json_decode($response->getBody()->getContents(), true);

            // check if the rates exist
            if(isset($response['rates']) && count($response['rates']) > 0) {
                foreach($response['rates'] as $key => $value) {
                    // update or create currency
                    Currency::updateOrCreate(['code' => $key], [
                        'rate' => $value,
                        'symbol' => $this->getCurrencySymbol($key)
                    ]);
                }
            }
        } catch(\Exception $e) {
            Log::warning($e->getCode() . ' : ' . $e->getMessage());
        }
    }

    /**
     * Get the currency symbol base on Country Code
     * @param $code
     * @return string
     */
    public function getCurrencySymbol($code)
    {
        $format = new NumberFormatter("en-GB@currency=$code", NumberFormatter::CURRENCY);
        $symbol = $format->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        // check if the string has a symbol
        if(preg_match('/[^a-zA-Z\d]/', $symbol)) {
            // remove alpha characters leaving just the symbol
            return preg_replace("/[a-zA-Z]/", "", $symbol);
        }
        return $symbol;
    }
}
