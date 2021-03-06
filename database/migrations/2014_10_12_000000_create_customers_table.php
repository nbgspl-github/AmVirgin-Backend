<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('customers', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('email')->unique()->nullable();
			$table->string('mobile')->unique()->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->string('avatar', \App\Library\Enums\Common\Constants::MaxFilePathLength);
			$table->boolean('active')->default(true);
			$table->integer('otp')->nullable();
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('customers');
	}
}
