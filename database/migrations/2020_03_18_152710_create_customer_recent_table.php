<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRecentTable extends Migration {
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up() {
		Schema::create('customer-recent', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('customerId');
			$table->enum('type', [\App\Constants\PageSectionType::Shop, \App\Constants\PageSectionType::Entertainment]);
			$table->unsignedBigInteger('key');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('customer-recent');
	}
}