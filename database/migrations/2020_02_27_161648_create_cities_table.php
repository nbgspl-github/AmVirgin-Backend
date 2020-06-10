<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('cities', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->comment('Name of city');
			$table->unsignedBigInteger('stateId')->comment('Reference to state');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('stateId')->references('id')->on(\App\Interfaces\Tables::States)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('cities');
		Schema::enableForeignKeyConstraints();
	}
}
