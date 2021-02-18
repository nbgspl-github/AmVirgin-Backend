<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('product_ratings', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('order_id');
			$table->string('orderNumber', 50);
			$table->unsignedBigInteger('seller_id');
			$table->unsignedBigInteger('customer_id');
			$table->unsignedBigInteger('product_id');
			$table->tinyInteger('stars')->default(0);
			$table->text('review')->nullable();
			$table->boolean('verified')->default(false);
			$table->enum('status', ['active', 'inactive'])->default('active');
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
		Schema::dropIfExists('product_ratings');
	}
}
