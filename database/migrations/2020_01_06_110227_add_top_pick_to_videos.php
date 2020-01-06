<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTopPickToVideos extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		if (!Schema::hasColumn('videos', 'topPick')) {
			Schema::table('videos', function (Blueprint $table){
				$table->boolean('topPick')->default(false)->after('rank');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		if (Schema::hasColumn('videos', 'topPick')) {
			Schema::table('videos', function (Blueprint $table){
				$table->dropColumn('topPick');
			});
		}
	}
}
