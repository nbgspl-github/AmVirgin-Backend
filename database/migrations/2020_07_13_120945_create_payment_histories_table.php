<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment-histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sellerId')->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->unsignedBigInteger('orderId')->nullable();
            $table->string('orderNumber')->nullable();
            $table->string('transactionId')->nullable();
            $table->string('status')->nullable();
            $table->string('paymentMode')->nullable();
            $table->date('date')->default(date('Y-m-d'));
            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_histories');
    }
}
