<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEchoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_echoes', function (Blueprint $table) {
            $table->integer('id', true)->signed();
            $table->integer('user_id')->signed()->index();
            $table->boolean('room_id')->nullable()->index();
            $table->boolean('target_id')->nullable()->index();
            $table->boolean('bot_id')->nullable()->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_echoes');
    }
}
