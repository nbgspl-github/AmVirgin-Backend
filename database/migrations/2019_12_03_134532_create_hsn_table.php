<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHsnTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('hsn-codes', function (Blueprint $table) {
			$table->string('hsnCode')->primary();
			$table->integer('taxRate')->default(0)->comment('Applicable tax rate for the given HSN code.');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('hsn-codes');
	}
}
