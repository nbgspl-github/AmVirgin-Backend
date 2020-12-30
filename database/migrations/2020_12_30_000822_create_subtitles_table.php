<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubtitlesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('video_subtitles', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('video_id');
			$table->unsignedBigInteger('video_source_id')->nullable();
			$table->unsignedBigInteger('video_language_id');
			$table->string('file', 1024);
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
		Schema::dropIfExists('video_subtitles');
	}
}