<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('featured_torrents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('torrent_id');
            $table->nullableTimestamps();
        });

        Schema::table('featured_torrents', function (Blueprint $table) {
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
        Schema::dropIfExists('featured_torrents');
    }

}