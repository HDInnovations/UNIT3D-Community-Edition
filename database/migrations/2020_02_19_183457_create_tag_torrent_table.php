<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTorrentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('tag_torrent', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('torrent_id');
            $table->string('tag_name');
        });

        Schema::table('tag_torrent', function (Blueprint $table) {
            $table->index('tag_name');
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
        Schema::dropIfExists('tag_torrent');
    }

}