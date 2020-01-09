<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtitleFileToVideoSources extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('video-sources', function (Blueprint $table){
			$table->string('subtitle', 4096)->after('mediaQualityId')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('video-sources', function (Blueprint $table){
			$table->dropColumn('subtitle');
		});
	}
}
