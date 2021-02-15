<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('rentals', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('customer_id');
			$table->unsignedBigInteger('video_id');
			$table->unsignedBigInteger('transaction_id');
			$table->integer('amount')->default(0);
			$table->timestamp('rented_at')->nullable();
			$table->timestamp('valid_from')->nullable();
			$table->timestamp('valid_until')->nullable();
			$table->timestamp('playback_started_at')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists('rentals');
	}
}