<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->mediumText('overview');
            $table->string('production_code')->nullable();
            $table->integer('season_number');
            $table->integer('season_id')->index();
            $table->string('still')->nullable();
            $table->integer('tv_id');
            $table->string('type')->nullable();
            $table->string('vote_average');
            $table->integer('vote_count');
            $table->string('air_date')->nullable();
            $table->integer('episode_number');
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
        Schema::dropIfExists('episodes');
    }
}
