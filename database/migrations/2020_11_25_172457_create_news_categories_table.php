<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('news_categories', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name')->comment('Name or title of category');
			$table->string('description', 2048)->nullable()->comment('Description for this category');
			$table->unsignedBigInteger('parentId')->nullable()->comment('Parent of this child category, available only if this is a child category');
			$table->tinyInteger('order')->default(0)->comment('Defines a order in which this category would appear in listing');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists('news_categories');
	}
}
