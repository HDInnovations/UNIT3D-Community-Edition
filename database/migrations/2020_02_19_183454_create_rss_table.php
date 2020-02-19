<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::create('rss', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->default(0);
            $table->string('name')->default('Default');
            $table->integer('user_id')->default(1);
            $table->integer('staff_id')->default(0);
            $table->boolean('is_private')->default(0);
            $table->boolean('is_torrent')->default(0);
            $table->softDeletes();
            $table->nullableTimestamps();
        });

        Schema::table('rss', function (Blueprint $table) {
            $table->index('is_private');
            $table->index('is_torrent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('rss');
    }

}