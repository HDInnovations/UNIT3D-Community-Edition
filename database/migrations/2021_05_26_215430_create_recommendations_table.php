<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('poster')->nullable();
            $table->string('vote_average')->nullable();
            $table->date('release_date')->nullable();
            $table->date('first_air_date')->nullable();

            $table->unsignedBigInteger('movie_id')->nullable()->index();
            $table->foreign('movie_id')->references('id')->on('movie')->onDelete('cascade');

            $table->unsignedBigInteger('recommendation_movie_id')->nullable()->index();
            $table->foreign('recommendation_movie_id')->references('id')->on('movie')->onDelete('cascade');

            $table->unsignedBigInteger('tv_id')->nullable()->index();
            $table->foreign('tv_id')->references('id')->on('tv')->onDelete('cascade');

            $table->unsignedBigInteger('recommendation_tv_id')->nullable()->index();
            $table->foreign('recommendation_tv_id')->references('id')->on('tv')->onDelete('cascade');

            $table->unique(['movie_id', 'recommendation_movie_id']);
            $table->unique(['tv_id', 'recommendation_tv_id']);
        });
    }
};
