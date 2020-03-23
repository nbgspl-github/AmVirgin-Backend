<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInSeller extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::table('sellers', function (Blueprint $table){
			$table->string('pinCode')->nullable()->after('cityId');
			$table->string('address')->nullable()->after('pinCode');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::table('sellers', function (Blueprint $table){
			Schema::dropColumn(['pinCode', 'address']);
		});
	}
}
