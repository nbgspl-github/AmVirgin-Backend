<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('shipping-addresses', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('customerId');
			$table->string('name');
			$table->string('mobile', 15);
			$table->string('alternateMobile', 15)->nullable();
			$table->string('pinCode', 10);
			$table->unsignedBigInteger('stateId');
			$table->string('address');
			$table->string('locality');
			$table->unsignedBigInteger('cityId');
			$table->string('type', 10)->default('home');
			$table->boolean('saturdayWorking')->default(false);
			$table->boolean('sundayWorking')->default(false);
			$table->boolean('default')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('shipping-addresses');
	}
}
