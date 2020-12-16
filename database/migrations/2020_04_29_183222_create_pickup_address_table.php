<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupAddressTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('seller-pickup-addresses', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('sellerId')->unique();
			$table->string('firstLine')->nullable();
			$table->string('secondLine')->nullable();
			$table->string('pinCode')->nullable();
			$table->unsignedBigInteger('city')->nullable();
			$table->unsignedBigInteger('state')->nullable();
			$table->unsignedBigInteger('country')->nullable();
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('sellerId')->references('id')->on(\App\Library\Enums\Common\Tables::Sellers)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('seller-pickup-addresses');
	}
}
