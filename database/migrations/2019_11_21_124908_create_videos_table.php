<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('videos', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title', 500);
			$table->string('slug', 600);
			$table->string('description', 2000);
			$table->time('duration')->nullable();
			$table->date('released');
			$table->string('cast', 500);
			$table->string('director')->nullable();
			$table->string('trailer', 1024);
			$table->string('poster', 1024);
			$table->string('backdrop', 1024);
			$table->unsignedBigInteger('genreId');
			$table->string('qualitySlug', 500)->nullable();
			$table->string('languageSlug', 500)->nullable();
			$table->float('rating', 3, 2)->default(0.0);
			$table->string('pgRating')->nullable();
			$table->string('type', 25);
			$table->bigInteger('hits')->default(0);
			$table->boolean('trending')->default(false);
			$table->integer('rank')->default(0)->comment('0 means no rank assigned and with that, this is just a normal one');
			$table->boolean('topPick')->default(false);
			$table->boolean('showOnHome')->default(false);
			$table->string('subscriptionType')->comment('free, paid, subscription');
			$table->float('price')->default(0.00)->comment('In case of paid feature, check this column');
			$table->boolean('hasSeasons')->default(false);
			$table->integer('seasons')->default(0);
			$table->boolean('active')->default(true);
			$table->boolean('pending')->default(true);
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
		Schema::dropIfExists('videos');
	}
}