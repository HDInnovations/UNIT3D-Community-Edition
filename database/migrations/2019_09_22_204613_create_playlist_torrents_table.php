<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_torrents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->nullable();
            $table->integer('playlist_id')->default(0)->index();
            $table->integer('torrent_id')->default(0)->index();
            $table->integer('tmdb_id')->default(0)->index();

            $table->unique(['playlist_id', 'torrent_id', 'tmdb_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_torrents');
    }
}
