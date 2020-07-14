<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review-ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sellerId')->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->unsignedBigInteger('orderId')->nullable();
            $table->unsignedBigInteger('productId')->nullable();
            $table->string('orderNumber')->nullable();
            $table->string('rate')->nullable();
            $table->string('commentMsg')->nullable(); 
            $table->date('date')->default(date('Y-m-d'));
            $table->boolean('status')->default(false);
            $table->timestamps();            
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review-ratings');
    }
}
