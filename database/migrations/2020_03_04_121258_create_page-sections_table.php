<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSectionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('page-sections', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->string('type')->comment('Type of section...one of entertainment, shop, etc');
			$table->mediumInteger('visibleItemCount')->default(10);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('page-sections');
	}
}
