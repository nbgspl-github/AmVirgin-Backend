<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategorybannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category-banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',500);
            $table->integer('order');
            $table->string('image', 4096);    
            $table->boolean('status')->default(false);    
            $table->string('sectionTitle',500);
            $table->string('layoutType',100);
            $table->timestamp('validFrom');
            $table->timestamp('validUntil');
            $table->boolean('hasValidity')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category-banner');
    }
}
