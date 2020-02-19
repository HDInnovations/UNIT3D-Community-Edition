<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('album_id');
            $table->string('image');
            $table->string('description');
            $table->string('type');
            $table->integer('downloads')->nullable();
            $table->nullableTimestamps();
        });

        Schema::table('images', function (Blueprint $table) {
            $table->index('album_id', 'images_album_id_foreign');

            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }

}