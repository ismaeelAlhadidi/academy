<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('for_who')->nullable();
            $table->text('for_who_not')->nullable();
            $table->text('notes')->nullable();
            $table->text('benefits')->nullable();
            $table->double('price');
            $table->double('duration');
            $table->string('poster')->default('/images/static/offer-default.jpg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions_offers');
    }
}
