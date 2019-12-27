<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoSeasonsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('video-seasons', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('title', 500);
			$table->string('slug', 1000);
			$table->string('description', 5000);
			$table->string('cast', 1000);
			$table->string('director', 256)->nullable();
			$table->boolean('replaceParentOverview')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('video-seasons');
	}
}
