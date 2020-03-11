<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopSlidersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('shop-sliders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->string('description', 2048)->nullable();
			$table->string('banner', 4096);
			$table->string('target', 2048)->nullable();
			$table->integer('rating')->default(0);
			$table->boolean('active')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('shop-sliders');
	}
}
