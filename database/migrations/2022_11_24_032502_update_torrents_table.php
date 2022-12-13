<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('torrents', function (Blueprint $table) {
            $table->integer('imdb')->unsigned()->change();
            $table->integer('tvdb')->unsigned()->change();
            $table->integer('tmdb')->unsigned()->change();
            $table->integer('mal')->unsigned()->change();
        });
    }
};
