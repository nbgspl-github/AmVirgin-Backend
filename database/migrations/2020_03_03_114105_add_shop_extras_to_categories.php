<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopExtrasToCategories extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('categories', function (Blueprint $table) {
			$table->boolean('brandInFocus')->default(false)->after('icon');
			$table->boolean('popularCategory')->default(false)->after('popularCategory');
			$table->boolean('trendingNow')->default(false)->after('trendingNow');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('categories', function (Blueprint $table) {
			$table->dropColumn(['brandInFocus', 'popularCategory', 'trendingNow']);
		});
	}
}
