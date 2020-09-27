<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsOnlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions_onlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('sessions_offer_id')->constrained('sessions_offers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('payment_id')->constrained('payments');
            $table->datetime('time');
            $table->boolean('admission')->default(false);
            $table->boolean('taken')->default(false);
            $table->datetime('request_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions_onlines');
    }
}
