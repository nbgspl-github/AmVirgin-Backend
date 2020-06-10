<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateProductTokensTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('product-tokens', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('token');
			$table->unsignedBigInteger('sellerId');
			$table->ipAddress('createdFor')->default(request()->ip());
			$table->timestamp('validUntil')->default(Carbon::now()->addHours(2)->toDateTimeString());
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('product-tokens');
	}
}
