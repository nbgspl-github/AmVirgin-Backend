<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('seller_transactions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('seller_id');
			$table->double('amount_requested', 10, 2)->default(0.0);
			$table->double('amount_received', 10, 2)->default(0.0);
			$table->string('bank_account', 25);
			$table->string('reference_id')->comment('Could be the transaction id of RZP order or any reference number');
			$table->enum('status', \App\Library\Enums\Transactions\Status::getValues())->default(\App\Library\Enums\Transactions\Status::Created);
			$table->timestamp('paid_at')->nullable();
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
		Schema::dropIfExists('seller_transactions');
	}
}