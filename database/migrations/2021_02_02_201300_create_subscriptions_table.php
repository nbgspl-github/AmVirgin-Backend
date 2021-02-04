<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create('customer_subscriptions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('customer_id');
			$table->unsignedBigInteger('subscription_plan_id');
			$table->unsignedBigInteger('transaction_id');
			$table->timestamp('valid_from')->nullable();
			$table->timestamp('valid_until')->nullable();
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
		Schema::dropIfExists('customer_subscriptions');
	}
}