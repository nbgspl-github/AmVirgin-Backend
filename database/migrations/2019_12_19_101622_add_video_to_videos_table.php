<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoToVideosTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('videos', function (Blueprint $table){
			$table->unsignedBigInteger('serverId')->after('genreId');
			$table->unsignedBigInteger('mediaLanguageId')->after('serverId');
			$table->unsignedInteger('mediaQualityId')->after('mediaLanguageId');
			$table->string('video', 4096)->after('previewUrl');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('videos', function (Blueprint $table){
			$table->dropColumn('video');
			$table->dropColumn('serverId');
			$table->dropColumn('mediaLanguageId');
			$table->dropColumn('mediaQualityId');
		});
	}
}
