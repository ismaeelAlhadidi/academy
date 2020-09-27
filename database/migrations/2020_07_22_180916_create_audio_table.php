<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pre_title');
            $table->string('title');
            $table->string('src');
            $table->string('driver');
            $table->string('mimi_type');
            $table->boolean('available')->default(false);
            $table->datetime('availability_time')->nullable();
            $table->text('description')->nullable();
            $table->string('poster_src')->default('/images/static/audio-default.jpg');
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
        Schema::dropIfExists('audio');
    }
}
