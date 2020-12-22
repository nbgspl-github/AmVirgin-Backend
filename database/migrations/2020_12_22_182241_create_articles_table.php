<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('articles', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->mediumText('content');
			$table->string('poster', 1024);
			$table->string('video', 1024)->nullable();
			$table->string('author');
			$table->boolean('published')->default(false);
			$table->integer('order')->default(0);
			$table->softDeletes();
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
		Schema::dropIfExists('articles');
	}
}