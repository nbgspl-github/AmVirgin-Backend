<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('product-attributes', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('productId')->comment('Product to which this attribute belongs');
			$table->unsignedBigInteger('attributeId')->comment('Reference to original attribute');
			$table->unsignedBigInteger('valueId')->nullable()->comment('Reference to attribute\'s value. Will only be available for option type attributes');
			$table->string('value', 10000)->nullable()->comment('Value of attribute. Will only be available for input type attributes');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('product-attributes');
	}
}
