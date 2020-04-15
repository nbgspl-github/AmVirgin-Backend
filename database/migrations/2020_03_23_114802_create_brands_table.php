<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('brands', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('slug', 500);
			$table->string('logo', \App\Constants\Constants::MaxFilePathLength)->nullable();
			$table->string('website', 2048)->nullable();
			$table->string('productSaleMarketPlace')->nullable();
			$table->string('sampleMRPTagImage', \App\Constants\Constants::MaxFilePathLength)->nullable();
			$table->boolean('isBrandOwner')->default(false);
			$table->string('documentProof', \App\Constants\Constants::MaxFilePathLength)->nullable();
			$table->string('documentType')->nullable();
			$table->json('documentExtras');
			$table->unsignedBigInteger('categoryId')->comment('Category to which this brand belongs.');
			$table->unsignedBigInteger('createdBy')->nullable()->comment('ID of seller who created or proposed this brand. Null if created by admin.');
			$table->enum('status', [\App\Models\Brand::Status]);
			$table->boolean('active')->default(false);
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('categoryId')->references('id')->on(\App\Interfaces\Tables::Categories)->onDelete('cascade');
				$table->foreign('createdBy')->references('id')->on(\App\Interfaces\Tables::Sellers)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('brands');
		Schema::enableForeignKeyConstraints();
	}
}
