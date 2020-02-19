<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEchoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('user_echoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('room_id')->nullable();
            $table->integer('target_id')->nullable();
            $table->integer('bot_id')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('user_echoes', function (Blueprint $table) {
            $table->index('room_id');
            $table->index('bot_id');
            $table->index('user_id');
            $table->index('target_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('user_echoes');
    }

}