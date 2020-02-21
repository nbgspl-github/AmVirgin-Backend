<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('carts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('sessionId', 100);
			$table->text('items');
			$table->unsignedBigInteger('addressId')->nullable();
			$table->unsignedBigInteger('customerId')->nullable();
			$table->integer('itemCount')->default(0);
			$table->float('subTotal')->default(0.0);
			$table->float('tax')->default(0.0);
			$table->integer('total')->default(0.0);
			$table->string('paymentMode', 50);
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
		Schema::dropIfExists('carts');
	}
}