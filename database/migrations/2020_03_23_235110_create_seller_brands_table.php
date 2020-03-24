<?php

use App\Constants\SellerBrandRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerBrandsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('seller-brands', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('brandId')->comment('Brand reference');
			$table->foreign('brandId')->references('id')->on(\App\Interfaces\Tables::Brands)->onDelete('cascade');
			$table->unsignedBigInteger('sellerId')->comment('Seller reference');
			$table->foreign('sellerId')->references('id')->on(\App\Interfaces\Tables::Sellers)->onDelete('cascade');
			$table->enum('status', [SellerBrandRequestStatus::Approved, SellerBrandRequestStatus::Received, SellerBrandRequestStatus::Rejected])->default(SellerBrandRequestStatus::Received)->comment('Whether the admin has approved the seller to start selling on this brand\'s name.');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('seller-brands');
	}
}