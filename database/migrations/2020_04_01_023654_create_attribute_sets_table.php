<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeSetsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('attribute-sets', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name', 500)->comment('Name of attribute set');
			$table->unsignedBigInteger('categoryId')->nullable()->comment('Category to which this set belongs');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('attribute-sets');
	}
}
