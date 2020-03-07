<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sliders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->string('description', 2048)->nullable();
			$table->string('poster', 4096);
			$table->string('target', 2048)->nullable();
			$table->integer('stars')->default(0);
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
		Schema::dropIfExists('sliders');
	}
}
