<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(0);
            $table->string('name');
            $table->text('description');
            $table->enum('frequency', [
                'day',
                'week',
                'month',
                'year'
            ]);
            $table->integer('frequency_interval')->default(1);
            $table->decimal('price');
            $table->string('currency')->default('GBP');
            $table->string('paypal_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function(Blueprint $table) {
            $table->datetime('subscribed_at')->nullable();
            $table->datetime('subscribed_until')->nullable();
            $table->datetime('subscription_cancelled_at')->nullable();
            $table->integer('subscription_method_id')->nullable();
            $table->integer('subscription_package_id')->nullable();
            $table->string('subscription_reference')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
