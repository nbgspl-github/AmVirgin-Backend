<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->integer('original_price');
            $table->unsignedBigInteger('user_id');
            $table->integer('offer_price');
            $table->float('tax');
            $table->string('attribute_name');
            $table->string('attribute_val')->nullable();
            $table->boolean('active');
            $table->integer('rating');
            $table->string('short_description');
            $table->string('long_description');
            $table->string('sku');
            $table->string('product_identifier');
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
        Schema::dropIfExists('products');
    }
}
