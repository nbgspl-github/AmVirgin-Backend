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
			$table->string('description', 5000);
			$table->time('duration');
			$table->string('cast', 1000);
			$table->string('director', 256)->nullable();
			$table->unsignedInteger('genreId');
			$table->float('rating,3,2')->default(0.0);
			$table->string('pgRating')->nullable();
			$table->string('type', 25);
			$table->bigInteger('hits')->default(0);
			$table->boolean('trending')->default(false);
			$table->boolean('showOnHomepage')->default(false);
			$table->integer('rank')->default(0)->comment('0 means no rank assigned and with that, this is just a normal one');
			$table->string('subscriptionType')->comment('free, paid, subscription');
			$table->float('price')->default(0.00)->comment('In case of paid feature, check this column');
			$table->boolean('hasSeasons')->default(false);
			$table->boolean('active')->default(true);
			$table->string('trailer', 4096);
			$table->string('poster', 4096);
			$table->string('backdrop', 4096);
			$table->string('meta', 65535);
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