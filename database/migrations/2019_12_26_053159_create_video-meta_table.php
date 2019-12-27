<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoMetaTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('video-meta', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('videoId');
			$table->string('name', 256);
			$table->string('displayName', 256);
			$table->string('value', 5000);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('video-meta');
	}
}
