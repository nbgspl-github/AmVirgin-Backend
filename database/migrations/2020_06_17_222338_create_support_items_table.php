<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportItemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up () {
		Schema::create('support_items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('email');
			$table->string('subject', 500);
			$table->string('description', 5000);
			$table->json('order_id');
			$table->string('callback_number');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down () {
		Schema::dropIfExists('support_items');
	}
}
