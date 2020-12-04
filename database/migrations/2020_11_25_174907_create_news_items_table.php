<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('news_items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('categoryId');
			$table->string('title', 255);
			$table->text('content')->nullable();
			$table->string('image', 2048)->nullable();
			$table->string('uploadedBy', 255)->nullable();
			$table->boolean('trending')->default(false);
			$table->integer('trendingRank')->default(0);
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
		Schema::dropIfExists('news_items');
	}
}
