<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_torrent', function (Blueprint $table) {
            $table->bigInteger('genre_id')->index();
            $table->bigInteger('torrent_id')->index();
            $table->primary(['genre_id', 'torrent_id']);
        });
    }
};
