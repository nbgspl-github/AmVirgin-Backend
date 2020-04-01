<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeSetItemsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('attribute-set-items', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('attributeSetId');
			$table->unsignedBigInteger('attributeId');
			$table->string('group');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('attribute-set-items');
	}
}
