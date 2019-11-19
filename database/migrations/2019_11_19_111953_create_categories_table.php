<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categories', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('slug');
			$table->unsignedBigInteger('parent_id')->nullable();
			$table->string('parent_slug')->nullable();
			$table->unsignedBigInteger('top_parent_id')->nullable();
			$table->string('top_parent_slug')->nullable();
			$table->string('title_meta_tag')->nullable();
			$table->text('description')->nullable();
			$table->integer('level')->default(1);
			$table->string('keywords')->nullable();
			$table->integer('order')->default(1);
			$table->integer('homepage_order')->default(1);
			$table->integer('visibility')->default(1);
			$table->boolean('homepage_visible')->default(1);
			$table->boolean('navigation_visible')->default(1);
			$table->string('storage')->nullable();
			$table->string('image_1', 256)->nullable();
			$table->string('image_2', 256)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('categories');
	}
}
