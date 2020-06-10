<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('customerId');
			$table->unsignedBigInteger('addressId');
			$table->string('orderNumber');
			$table->integer('quantity')->default(0);
			$table->float('subTotal')->default(0.0);
			$table->float('tax')->default(0.0);
			$table->integer('total')->default(0);
			$table->string('paymentMode');
			$table->string('transactionId')->nullable();
			$table->string('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('orders');
	}
}