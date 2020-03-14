<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('domesticwarranty ')->nullable()->after('sellingPrice');
            $table->string('internationalWarranty  ')->nullable()->after('domesticwarranty');
            $table->string('warrantySummary ')->nullable()->after('internationalWarranty');
            $table->string('warrantyServiceType  ')->nullable()->after('warrantySummary');
            $table->string('coveredInWarranty    ')->nullable()->after('warrantyServiceType');
            $table->string('notCoveredInWarranty   ')->nullable()->after('coveredInWarranty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['domesticwarranty','internationalWarranty ','warrantySummary','warrantyServiceType','coveredInWarranty','notCoveredInWarranty  ']);
        });
    }
}
