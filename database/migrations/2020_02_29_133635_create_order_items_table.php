<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('order_items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('orderId');
			$table->unsignedBigInteger('segmentId');
			$table->unsignedBigInteger('sellerId');
			$table->unsignedBigInteger('productId');
			$table->integer('quantity')->default(0);
			$table->float('price')->default(0.0);
			$table->double('originalPrice', 8, 2)->default(0.0);
			$table->double('sellingPrice', 8, 2)->default(0.0);
			$table->double('subTotal', 8, 2)->default(0.0);
			$table->double('total', 8, 2)->default(0.0);
			$table->boolean('returnable')->default(false);
			$table->enum('returnType', ['refund', 'replacement', 'both'])->default('refund');
			$table->tinyInteger('returnPeriod')->default(0);
			$table->timestamp('returnValidUntil')->nullable();
			$table->json('options');
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
		Schema::dropIfExists('order_items');
	}
}
