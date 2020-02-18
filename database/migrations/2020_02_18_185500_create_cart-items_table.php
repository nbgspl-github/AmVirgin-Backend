<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('cart-items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('cartId');
			$table->unsignedBigInteger('productId');
			$table->string('uniqueId');
			$table->integer('quantity');
			$table->float('itemTotal')->default(0.0);
			$table->json('attributes');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('cart-items');
	}
}
