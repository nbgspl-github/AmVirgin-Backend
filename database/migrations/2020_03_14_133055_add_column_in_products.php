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
           $table->string('hsn')->default(false)->after('hotDeal');
           $table->string('taxCode')->default(false)->after('hsn');
           $table->boolean('fullfilmentBy')->default(false)->after('taxCode');
           $table->string('procurementSla')->default(false)->after('fullfilmentBy');
           $table->integer('localShippingCost')->default(false)->after('procurementSla');
           $table->integer('zonalShippingCost')->default(false)->after('localShippingCost');
           $table->integer('internationalShippingCost')->default(false)->after('zonalShippingCost');
           $table->string('packageWeight')->default(false)->after('internationalShippingCost');
           $table->string('packageLenght')->default(false)->after('packageWeight');
           $table->string('packageHeight')->default(false)->after('packageLenght');
           $table->string('idealFor')->default(false)->after('packageHeight');
           $table->string('videoUrl')->default(false)->after('idealFor');
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
            $table->dropColumn(['sellingPrice','hsn','taxCode','fullfilmentBy','procurementSla','localShippingCost','zonalShippingCost','internationalShippingCost','packageWeight','packageLenght','packageHeight','idealFor','videoUrl']);
        });
    }
}
