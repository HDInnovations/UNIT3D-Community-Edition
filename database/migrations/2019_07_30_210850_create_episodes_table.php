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
        Schema::create('episodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->mediumText('overview')->nullable();
            $table->string('production_code')->nullable();
            $table->integer('season_number');
            $table->integer('season_id')->index();
            $table->string('still')->nullable();
            $table->integer('tv_id');
            $table->string('type')->nullable();
            $table->string('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->string('air_date')->nullable();
            $table->integer('episode_number')->nullable();
            $table->timestamps();
        });
    }
};
