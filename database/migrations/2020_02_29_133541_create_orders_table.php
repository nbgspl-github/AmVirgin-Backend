<?php

use App\Library\Enums\Orders\Payments\Methods;
use App\Library\Enums\Orders\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('customerId');
			$table->unsignedBigInteger('transactionId')->nullable();
			$table->unsignedBigInteger('addressId')->nullable();
			$table->unsignedBigInteger('billingAddressId')->nullable();
			$table->enum('paymentMode', Methods::getValues());
			$table->enum('status', Status::getValues())->default(Status::Pending);
			$table->tinyInteger('quantity')->default(0);
			$table->float('tax')->default(0.0);
			$table->double('subTotal', 10, 2)->default(0.0);
			$table->integer('total')->default(0);
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
		Schema::dropIfExists('orders');
	}
}