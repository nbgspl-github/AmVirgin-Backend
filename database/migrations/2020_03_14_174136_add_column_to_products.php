<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('domesticwarranty')->nullable()->after('sellingPrice');
            $table->text('internationalWarranty')->nullable()->after('domesticwarranty');
            $table->text('warrantySummary')->nullable()->after('internationalWarranty');
            $table->text('warrantyServiceType')->nullable()->after('warrantySummary');
            $table->text('coveredInWarranty')->nullable()->after('warrantyServiceType');
            $table->text('notCoveredInWarranty')->nullable()->after('coveredInWarranty');
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
