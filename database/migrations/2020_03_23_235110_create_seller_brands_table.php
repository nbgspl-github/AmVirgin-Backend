<?php

use App\Models\SellerBrand;
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
			$table->unsignedBigInteger('sellerId')->comment('Seller reference');
			$table->enum('status', [SellerBrand::Status['Approved'], SellerBrand::Status['Rejected'], SellerBrand::Status['Received']])->default(SellerBrand::Status['Received'])->comment('Whether the admin has approved the seller to start selling on this brand\'s name.');
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