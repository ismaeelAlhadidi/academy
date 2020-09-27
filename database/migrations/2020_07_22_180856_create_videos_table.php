<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pre_title');
            $table->string('title');
            $table->string('src');
            $table->string('driver');
            $table->string('mimi_type');
            $table->boolean('available')->default(false);
            $table->datetime('availability_time')->nullable();
            $table->string('poster_src')->default('/images/static/video-default.jpg');
            $table->string('form_key')->nullable();
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
        Schema::dropIfExists('videos');
    }
}
