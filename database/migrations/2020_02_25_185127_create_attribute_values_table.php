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
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('attribute-values');
	}
}