<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('product-attributes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('productId');
			$table->unsignedBigInteger('attributeId');
			$table->unsignedBigInteger('valueId');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('product-attributes');
	}
}
