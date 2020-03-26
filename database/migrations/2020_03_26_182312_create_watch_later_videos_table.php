<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchLaterVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watch_later_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('video_id')->nullable();
            $table->string('video_type')->nullable();
            $table->string('customer_type')->nullable();
            $table->string('customer_ip')->nullable();
            $table->text('customer_user_agent')->nullable();
            $table->integer('video_count')->nullable();
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
        Schema::dropIfExists('watch_later_videos');
    }
}
