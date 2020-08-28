<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function(Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(0);
            $table->enum('mode', [
                'live',
                'test'
            ]);
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('subscription_model')->nullable();
            $table->string('subscription_controller')->nullable();
            $table->string('payment_controller')->nullable();
            $table->text('details');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('payment_methods')->insert([
            'active' => true,
            'name' => 'Paypal',
            'subscription_model' => '\MissionControl\Payments\Modules\Packages\Models\PaypalPackage',
            'subscription_controller' => '\MissionControl\Payments\Modules\PaymentMethods\Controllers\PaypalSubscriptionController',
            'payment_controller' => '\MissionControl\Payments\Modules\PaymentMethods\Controllers\PaypalPaymentController',
            'details' => json_encode([
                'client_id' => '',
                'client_secret' => '',
                'webhook_id' => ''
            ]),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('payment_methods')->insert([
            'active' => true,
            'name' => 'Stripe',
            'payment_controller' => '\MissionControl\Payments\Modules\PaymentMethods\Controllers\StripePaymentController',
            'details' => json_encode([
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => ''
            ]),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('payment_methods')->insert([
            'active' => true,
            'name' => 'Amazon Pay',
            'payment_controller' => '\MissionControl\Payments\Modules\PaymentMethods\Controllers\AmazonPaymentController',
            'details' => json_encode([
                'merchant_id' => '',
                'access_key' => '',
                'secret_access_key' => '',
                'client_id' => '',
                'client_secret' => ''
            ]),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('payment_methods')->insert([
            'active' => true,
            'name' => 'Secure Trading',
            'payment_controller' => '\MissionControl\Payments\Modules\PaymentMethods\Controllers\SecureTradingController',
            'details' => json_encode([
                'sitereference' => '',
            ]),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
