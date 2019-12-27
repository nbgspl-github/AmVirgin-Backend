<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoSourceTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('video-sources', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('videoId');
			$table->integer('seasonId')->nullable();
			$table->string('description', 5000);
			$table->bigInteger('hits')->default(0);
			$table->boolean('active')->default(true);
			$table->unsignedBigInteger('mediaLanguageId');
			$table->unsignedBigInteger('mediaQualityId');
			$table->string('file', 4096);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('video-sources');
	}
}
