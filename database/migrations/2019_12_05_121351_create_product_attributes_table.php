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
			$table->boolean('variantAttribute')->default(false)->comment('Whether the attribute these values belong to is a variant attribute?');
			$table->string('label')->comment('Label of attribute');
			$table->string('group')->comment('Group name of attribute');
			$table->string('value', 10000)->nullable()->comment('Value(s) of attribute');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('productId')->references('id')->on(\App\Interfaces\Tables::Products)->onDelete('cascade');
				$table->foreign('attributeId')->references('id')->on(\App\Interfaces\Tables::Attributes)->onDelete('cascade');
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
		Schema::dropIfExists('product-attributes');
		Schema::enableForeignKeyConstraints();
	}
}