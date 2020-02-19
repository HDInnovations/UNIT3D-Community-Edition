<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->integer('position');
            $table->string('icon')->default('none');
            $table->boolean('no_meta')->default(0);
            $table->boolean('music_meta')->default(0);
            $table->boolean('game_meta')->default(0);
            $table->boolean('tv_meta')->default(0);
            $table->boolean('movie_meta')->default(0);
            $table->integer('num_torrent')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }

}