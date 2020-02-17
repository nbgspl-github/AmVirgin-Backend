<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('quotes', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('sessionId', 29);
			$table->unsignedBigInteger('addressId')->nullable();
			$table->unsignedBigInteger('customerId')->nullable();
			$table->integer('itemCount')->default(0);
			$table->float('subTotal')->default(0.0);
			$table->float('cgst')->default(0.0);
			$table->float('sgst')->default(0.0);
			$table->string('paymentMode', 50);
			$table->string('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('quotes');
	}
}