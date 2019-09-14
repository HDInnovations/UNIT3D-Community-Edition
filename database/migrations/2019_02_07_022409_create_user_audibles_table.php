<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAudiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_audibles', function (Blueprint $table) {
            $table->integer('id', true)->signed();
            $table->integer('user_id')->signed()->index();
            $table->boolean('room_id')->nullable()->index();
            $table->boolean('target_id')->nullable()->index();
            $table->boolean('bot_id')->nullable()->index();
            $table->boolean('status')->default(0)->index();
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
        Schema::dropIfExists('user_audibles');
    }
}
