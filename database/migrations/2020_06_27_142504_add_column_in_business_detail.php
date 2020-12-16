<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInBusinessDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller-business-details', function (Blueprint $table) {
            $table->string('pan')->nullable();
            $table->boolean('panVerified')->default(false);
	        $table->string('panProofDocument', \App\Library\Enums\Common\Constants::MaxFilePathLength)->nullable();
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
            $table->dropColumn(['pan','panVerified','panProofDocument']);
        });
    }
}
