<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeasonsToVideosTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		if (!Schema::hasColumn('videos', 'seasons')) {
			Schema::table('videos', function (Blueprint $table){
				$table->integer('seasons')->default(0)->after('hasSeasons');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		if (Schema::hasColumn('videos', 'seasons')) {
			Schema::table('videos', function (Blueprint $table){
				$table->dropColumn('seasons');
			});
		}
	}
}
