<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paymentmethod_id');
            $table->integer('user_id')->nullable();
            $table->integer('type_id');
            $table->string('type');
            $table->enum('status', ['pending', 'complete', 'failed', 'refunded']);
            $table->string('reference');
            $table->string('vendor_reference')->nullable();
            $table->string('currency');
            $table->decimal('subtotal');
            $table->decimal('vat');
            $table->decimal('total');
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
