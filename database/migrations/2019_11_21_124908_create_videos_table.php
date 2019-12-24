<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('videos', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('title', 256);
			$table->string('description', 2000)->nullable();
			$table->smallInteger('type'); // [Movie or Series or Live]
			$table->date('released')->nullable();
			$table->float('rating', 3, 2)->default(0.0);
			$table->integer('hits')->default(0);
			$table->unsignedBigInteger('genreId');
			$table->unsignedBigInteger('mediaLanguageId');
			$table->unsignedInteger('mediaQualityId');
			$table->string('poster', 256)->nullable();
			$table->string('backdrop', 256)->nullable();
			$table->string('video', 4096);
			$table->string('previewUrl', 2048)->nullable();
			$table->boolean('visibleOnHome')->default(false);
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
	public function down(){
		Schema::dropIfExists('videos');
	}
}