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
			$table->boolean('brandInFocus')->default(false);
			$table->boolean('popularCategory')->default(false);
			$table->boolean('trendingNow')->default(false);
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
