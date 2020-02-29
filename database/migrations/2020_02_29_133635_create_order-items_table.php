<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('order-items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('orderId');
			$table->unsignedBigInteger('productId');
			$table->integer('quantity')->default(0);
			$table->float('price')->default(0.0);
			$table->integer('total')->default(0);
			$table->text('options');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('order-items');
	}
}
