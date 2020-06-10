<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaServersTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('media-servers', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name');
			$table->ipAddress('ipAddress')->nullable();
			$table->boolean('useAuth')->default(false);
			$table->string('basePath')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('media-servers');
	}
}
