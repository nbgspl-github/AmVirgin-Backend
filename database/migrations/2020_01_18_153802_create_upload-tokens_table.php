<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadTokensTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('upload-tokens', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('token', 50);
			$table->unsignedBigInteger('key');
			$table->string('model', 1000);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('upload-tokens');
	}
}
