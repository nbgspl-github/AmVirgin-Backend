<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlansTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('subscription-plans', function (Blueprint $table){
			$table->increments('id');
			$table->string('name', 100);
			$table->string('slug', 250);
			$table->string('description', 5000);
			$table->float('originalPrice')->default(0);
			$table->float('offerPrice')->default(0);
			$table->string('banner', 4096)->nullable();
			$table->integer('duration')->default(0)->comment('0 means unlimited, anything else means duration');
			$table->boolean('active')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('subscription-plans');
	}
}