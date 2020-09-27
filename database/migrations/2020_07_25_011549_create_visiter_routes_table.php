<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisiterRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visiter_routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('visiter_id')->constrained('visiters')->onDelete('cascade');
            $table->text('reference')->nullable();
            $table->integer('count');
            $table->string('route')->nullable();
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
        Schema::dropIfExists('visiter_routes');
    }
}
