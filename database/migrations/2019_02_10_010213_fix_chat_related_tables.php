<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixChatRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_echoes', function (Blueprint $table) {
            $table->integer('room_id')->change();
            $table->integer('bot_id')->change();
            $table->integer('target_id')->change();
        });
        Schema::table('user_audibles', function (Blueprint $table) {
            $table->integer('room_id')->change();
            $table->integer('bot_id')->change();
            $table->integer('target_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_echoes', function (Blueprint $table) {
            //
        });
    }
}
