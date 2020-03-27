<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValuesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('attribute-values', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('attributeId')->comment('Attribute to which these values belong');
			$table->unsignedBigInteger('categoryId')->comment('Since an attribute can have multiple distinct type values across several categories, we use this to find a set of values valid for a particular category');
			$table->string('value', '10000')->comment('Value of attribute');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('attributeId')->references('id')->on(\App\Interfaces\Tables::Attributes)->onDelete('cascade');
				$table->foreign('categoryId')->references('id')->on(\App\Interfaces\Tables::Categories)->onDelete('cascade');
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
		Schema::dropIfExists('attribute-values');
		Schema::enableForeignKeyConstraints();
	}
}