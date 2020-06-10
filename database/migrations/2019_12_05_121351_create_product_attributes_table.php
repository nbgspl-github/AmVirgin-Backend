<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::create('product-attributes', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('productId')->comment('Product to which this attribute belongs');
			$table->unsignedBigInteger('attributeId')->comment('Reference to original attribute');
			$table->boolean('variantAttribute')->default(false)->comment('Whether the attribute these values belong to is a variant attribute?');
			$table->boolean('showInCatalogListing')->default(false)->comment('Whether this attribute and its values will be shown in catalog listing?');
			$table->boolean('visibleToCustomers')->comment('Whether this attribute and its value be visible to customer?');
			$table->string('interface')->default(\App\Models\Attribute::Interface['TextLabel'])->comment('What kind of interface will be shown for attribute\'s value(s)?');
			$table->string('label')->comment('Label of attribute');
			$table->string('group')->comment('Group name of attribute');
			$table->json('value')->comment('Value(s) of attribute');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('productId')->references('id')->on(\App\Interfaces\Tables::Products)->onDelete('cascade');
				$table->foreign('attributeId')->references('id')->on(\App\Interfaces\Tables::Attributes)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('product-attributes');
		Schema::enableForeignKeyConstraints();
	}
}