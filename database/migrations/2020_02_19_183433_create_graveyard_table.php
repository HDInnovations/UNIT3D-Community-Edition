<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraveyardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('graveyard', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('torrent_id');
            $table->unsignedBigInteger('seedtime');
            $table->boolean('rewarded')->default(0);
            $table->nullableTimestamps();
        });

        Schema::table('graveyard', function (Blueprint $table) {
            $table->index('torrent_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('graveyard');
    }

}