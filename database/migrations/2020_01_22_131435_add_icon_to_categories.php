<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconToCategories extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('categories', function (Blueprint $table){
			$table->string('icon', 4096)->nullable()->after('parentId');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		if (Schema::hasColumn('categories', 'icon')) {
			Schema::table('categories', function (Blueprint $table){
				$table->dropColumn('icon');
			});
		}
	}
}
