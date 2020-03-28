<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('categories', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name')->comment('Name of category');
			$table->unsignedBigInteger('parentId')->comment('Parent of this child category, available only if this is a child category');
			$table->string('description', 2048)->nullable()->comment('Description for this category');
			$table->boolean('visibility')->default(true);
			$table->string('poster', 4096)->nullable();
			$table->string('icon', 4096)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('categories');
	}
}