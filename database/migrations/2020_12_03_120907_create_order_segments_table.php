<?php

use App\Library\Enums\Orders\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSegmentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('order_segments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('orderId');
			$table->unsignedBigInteger('sellerId');
			$table->unsignedBigInteger('shipmentId')->nullable();
			$table->unsignedBigInteger('invoiceId')->nullable();
			$table->enum('status', Status::getValues())->default(Status::Pending);
			$table->tinyInteger('quantity')->default(0);
			$table->float('tax')->default(0.0);
			$table->double('subTotal', 10, 2)->default(0.0);
			$table->integer('total')->default(0);
			$table->timestamp('expected_at')->nullable();
			$table->timestamp('fulfilled_at')->nullable();
			$table->timestamp('cancelled_at')->nullable();
			$table->softDeletes();
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
		Schema::dropIfExists('order_segments');
	}
}
