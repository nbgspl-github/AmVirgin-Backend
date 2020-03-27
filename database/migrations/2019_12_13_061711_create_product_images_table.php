<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('product-images', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('productId')->comment('Product to which these images belong');
			$table->string('path', \App\Constants\Constants::MaxFilePathLength);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('product-images');
	}
}
