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
			$table->string('path', \App\Constants\Constants::MaxFilePathLength)->comment('Relative path of the file on the specified disk');
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('productId')->references('id')->on(\App\Interfaces\Tables::Products)->onDelete('cascade');
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
		Schema::dropIfExists('product-images');
		Schema::enableForeignKeyConstraints();
	}
}
