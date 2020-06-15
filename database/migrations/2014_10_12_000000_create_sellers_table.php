<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up () : void {
		Schema::create('sellers', static function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('password');
			$table->string('businessName')->nullable();
			$table->string('description', 2000)->nullable();
			$table->string('email')->unique()->nullable();
			$table->string('mobile')->unique()->nullable();
			$table->unsignedBigInteger('countryId')->nullable();
			$table->unsignedBigInteger('stateId')->nullable();
			$table->unsignedBigInteger('cityId')->nullable();
			$table->string('pinCode')->nullable();
			$table->string('address')->nullable();
			$table->float('rating', 2, 1)->default(0.0);
			$table->integer('otp')->nullable();
			$table->string('avatar', 4096)->nullable();
			$table->boolean('active')->default(true);
			$table->integer('productsAdded')->default(0);
			$table->integer('productsSold')->default(0);
			$table->boolean('mouAgreed')->default(false);
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down () : void {
		Schema::dropIfExists('sellers');
	}
}