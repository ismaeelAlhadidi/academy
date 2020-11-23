<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSingleVideoFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_video_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('video_id')->constrained('videos');
            $table->foreignId('user_id')->nullable()->constrained('users')->default('null');
            $table->foreignId('visiter_id')->nullable()->constrained('visiters')->default('null');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('send_mail')->default(false);
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
        Schema::dropIfExists('single_video_forms');
    }
}
