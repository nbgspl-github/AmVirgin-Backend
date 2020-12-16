<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('seller-bank-details', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->unsignedBigInteger('sellerId')->unique();
			$table->string('accountHolderName')->nullable();
			$table->string('accountNumber')->nullable();
			$table->boolean('accountNumberVerified')->default(false);
			$table->string('bankName')->nullable();
			$table->unsignedBigInteger('cityId')->nullable();
			$table->unsignedBigInteger('stateId')->nullable();
			$table->unsignedBigInteger('countryId')->nullable();
			$table->string('branch')->nullable();
			$table->string('ifsc')->nullable();
			$table->string('businessType')->nullable();
			$table->string('pan')->nullable();
			$table->boolean('panVerified')->default(false);
			$table->string('addressProofType')->nullable();
			$table->string('addressProofDocument', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
			$table->boolean('addressProofVerified')->default(false);
			$table->string('cancelledCheque', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
			$table->boolean('cancelledChequeVerified')->default(false);
			$table->timestamps();

			if (appEnvironment(AppEnvironmentProduction)) {
				$table->foreign('sellerId')->references('id')->on(\App\Library\Enums\Common\Tables::Sellers)->onDelete('cascade');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::dropIfExists('seller-bank-details');
	}
}
