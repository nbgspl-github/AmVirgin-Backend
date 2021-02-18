<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('orderId');
			$table->string('rzpOrderId');
			$table->string('paymentId')->nullable();
			$table->string('signature')->nullable();
			$table->boolean('verified')->default(false);
			$table->double('amountRequested', 10, 2)->default(0.0);
			$table->double('amountReceived', 10, 2)->default(0.0);
			$table->string('currency', 10)->default('INR');
			$table->integer('attempts')->default(0);
			$table->string('status', 50)->default('created');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists('transactions');
	}
}
