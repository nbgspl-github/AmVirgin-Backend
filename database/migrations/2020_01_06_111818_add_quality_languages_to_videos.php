<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQualityLanguagesToVideos extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('videos', function (Blueprint $table){
			$table->string('qualitySlug', 500)->nullable()->after('genreId');
			$table->string('languageSlug', 500)->nullable()->after('qualitySlug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('videos', function (Blueprint $table){
			$table->dropColumn('qualitySlug');
			$table->dropColumn('languageSlug');
		});
	}
}