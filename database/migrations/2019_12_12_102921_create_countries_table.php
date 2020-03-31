<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('countries', function (Blueprint $table){
			$table->increments('id');
			$table->string('initials', 5)->comment('Initials of country name');
			$table->string('name')->comment('Name of country');
			$table->integer('phoneCode')->comment('International Phone Code');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('countries');
		Schema::enableForeignKeyConstraints();
	}
}
