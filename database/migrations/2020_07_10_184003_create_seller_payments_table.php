<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPaymentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('seller_payments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('seller_id');
			$table->string('transaction_id', 50);
			$table->string('neft_id', 50);
			$table->timestamp('paid_at');
			$table->string('bank_account');
			$table->double('amount', 10, 2)->default(0.0);
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
		Schema::dropIfExists('seller_payments');
	}
}
