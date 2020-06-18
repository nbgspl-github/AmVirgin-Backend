<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up () {
		Schema::create('support-tickets', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('sellerId');
			$table->string('email');
			$table->string('subject', 500);
			$table->string('description', 5000);
			$table->json('orderId');
			$table->string('callbackNumber');
			$table->json('attachments');
			$table->string('status')->default('open');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down () {
		Schema::dropIfExists('support-tickets');
	}
}
