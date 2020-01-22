<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToCart extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('cart', function (Blueprint $table){
			$table->integer('quantity')->default(0)->after('cartId');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		if (Schema::hasColumn('cart', 'quantity')) {
			Schema::table('cart', function (Blueprint $table){
				$table->dropColumn('quantity');
			});
		}
	}
}
