<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('playlist_torrents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->nullable();
            $table->integer('playlist_id')->default(0);
            $table->integer('torrent_id')->default(0);
            $table->integer('tmdb_id')->default(0);
        });

        Schema::table('playlist_torrents', function (Blueprint $table) {
            $table->index('playlist_id');
            $table->index('tmdb_id');
            $table->unique(['playlist_id', 'torrent_id', 'tmdb_id'], 'playlist_torrents_playlist_id_torrent_id_tmdb_id_unique');
            $table->index('torrent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_torrents');
    }

}