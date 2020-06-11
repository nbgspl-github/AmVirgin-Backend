<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up () {
		Schema::create('seller-orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('sellerId');
			$table->unsignedBigInteger('customerId');
			$table->unsignedBigInteger('orderId');
			$table->string('orderNumber');
			$table->string('status')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down () {
		Schema::dropIfExists('seller-orders');
	}
}
