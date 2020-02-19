<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('name');
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('is_private')->default(0);
            $table->boolean('is_pinned')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->nullableTimestamps();
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->index('is_private');
            $table->index('is_featured');
            $table->index('user_id');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('playlists');
    }

}