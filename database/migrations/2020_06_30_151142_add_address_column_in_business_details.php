<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressColumnInBusinessDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller-business-details', function (Blueprint $table) {
            $table->string('addressProofType')->nullable();
	        $table->string('addressProofDocument', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
	        $table->boolean('addressProofVerified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller-business-details', function (Blueprint $table) {
            $table->dropColumn(['addressProofType','addressProofDocument','addressProofVerified']);
        });
    }
}
