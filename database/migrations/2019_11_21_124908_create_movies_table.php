<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('movies', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->string('description', 2000)->nullable();
			$table->bigInteger('movieDBId')->nullable();
			$table->bigInteger('imdbId')->nullable();
			$table->date('releaseDate')->nullable();
			$table->float('averageRating', 2, 2)->default(0.0);
			$table->integer('votes')->default(0);
			$table->float('popularity', 2, 2)->default(0.0);
			$table->string('genreId')->nullable();
			$table->string('poster', 256)->nullable();
			$table->string('backdrop', 256)->nullable();
			$table->string('previewUrl', 2048)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('movies');
	}
}
