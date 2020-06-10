<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('genres', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 250);
			$table->string('description', 1000)->nullable();
			$table->string('poster', 256)->nullable();
			$table->boolean('status')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('genres');
	}
}
