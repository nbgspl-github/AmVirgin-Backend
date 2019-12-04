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
        Schema::create('products', function (Blueprint $table){
            $table->bigIncrements('id');
			$table->string('name');
			$table->string('product_type');
			$table->unsignedBigInteger('category_id');
			$table->integer('country_id');
			$table->integer('state_id');
			$table->integer('city_id');
			$table->string('address')->nullable();
			$table->string('zip_code');
			$table->unsignedBigInteger('user_id');
			$table->boolean('active')->default(true);
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
