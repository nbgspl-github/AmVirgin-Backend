<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('seller-business-details', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sellerId')->unique();
            $table->string('name')->nullable()->comment('Business or company\'s name');
            $table->boolean('nameVerified')->default(false);
            $table->string('tan')->nullable()->comment('Tax Deduction & Account Number');
            $table->string('gstIN')->nullable()->comment('GST Identification number');
	        $table->string('gstCertificate', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
	        $table->boolean('gstINVerified')->default(false);
	        $table->string('signature', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
	        $table->boolean('signatureVerified')->default(false);
            $table->string('rbaFirstLine')->nullable();
            $table->string('rbaSecondLine')->nullable();
            $table->string('rbaPinCode')->nullable();
            $table->unsignedBigInteger('rbaCityId')->nullable();
            $table->unsignedBigInteger('rbaStateId')->nullable();
            $table->unsignedBigInteger('rbaCountryId')->nullable();
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
    public function down(): void
    {
        Schema::dropIfExists('seller-business-details');
    }
}
