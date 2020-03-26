<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimitiveTypesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('primitive-types', function (Blueprint $table){
			$table->string('typeCode', 10)->primary()->comment('Name of primitive type');
			$table->string('name')->comment('Name of primitive, used for display purposes only');
			$table->enum('primitiveType', ['string', 'bool', 'float', 'int'])->comment('Keyword used to identify primitive type');
			$table->string('usableFunction', 15)->comment('Built-in function use to convert a value to this primitive type');
			$table->boolean('measurable')->comment('Whether this primitive type can be measured by a range');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('primitive-types');
	}
}