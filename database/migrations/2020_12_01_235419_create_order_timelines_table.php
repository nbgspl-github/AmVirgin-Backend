<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTimelinesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('order_timelines', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('orderId');
			$table->enum('event', \App\Library\Enums\Orders\Status::getValues());
			$table->string('description', 1000)->nullable();
			$table->string('invokedBy')->nullable();
			$table->timestamp('timestamp');
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
		Schema::dropIfExists('order_timelines');
	}
}
