<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('states', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->comment('Name of state');
			$table->unsignedBigInteger('countryId')->comment('Reference to country');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('countryId')->references('id')->on(\App\Library\Enums\Common\Tables::Countries)->onDelete('cascade');
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
		Schema::dropIfExists('states');
		Schema::enableForeignKeyConstraints();
	}
}
