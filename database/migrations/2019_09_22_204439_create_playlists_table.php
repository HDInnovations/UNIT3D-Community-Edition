<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index();
            $table->string('name');
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('is_private')->default(0)->index();
            $table->boolean('is_pinned')->default(0)->index();
            $table->boolean('is_featured')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlists');
    }
}
