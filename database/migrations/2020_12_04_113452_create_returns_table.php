<?php

use App\Library\Enums\Orders\Returns\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('returns', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('order_id');
			$table->unsignedBigInteger('customer_id');
			$table->unsignedBigInteger('seller_id');
			$table->unsignedBigInteger('order_segment_id');
			$table->unsignedBigInteger('order_item_id');
			$table->enum('status', Status::getValues())->default(Status::Pending);
			$table->enum('return_type', ['refund', 'replacement']);
			$table->unsignedBigInteger('refund_shipment_id')->nullable();
			$table->unsignedBigInteger('replacement_shipment_id')->nullable();
			$table->timestamp('refund_initiated_at')->nullable();
			$table->timestamp('refund_completed_at')->nullable();
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
		Schema::dropIfExists('returns');
	}
}
