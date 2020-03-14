<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
           $table->integer('sellingPrice')->default(false)->after('hotDeal');
           $table->string('hsn')->nullable()->after('hotDeal');
           $table->string('taxCode')->nullable()->after('hsn');
           $table->boolean('fullfilmentBy')->default(false)->after('taxCode');
           $table->string('procurementSla')->nullable()->after('fullfilmentBy');
           $table->integer('localShippingCost')->default(0)->after('procurementSla');
           $table->integer('zonalShippingCost')->default(0)->after('localShippingCost');
           $table->integer('internationalShippingCost')->default(0)->after('zonalShippingCost');
           $table->string('packageWeight')->nullable()->after('internationalShippingCost');
           $table->string('packageLength')->nullable()->after('packageWeight');
           $table->string('packageHeight')->nullable()->after('packageLength');
           $table->string('idealFor')->nullable()->after('packageHeight');
           $table->string('videoUrl')->nullable()->after('idealFor');
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
            $table->dropColumn(['sellingPrice','hsn','taxCode','fullfilmentBy','procurementSla','localShippingCost','zonalShippingCost','internationalShippingCost','packageWeight','packageLength','packageHeight','idealFor','videoUrl']);
        });
    }
}
