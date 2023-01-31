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
        Schema::create('movie', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('title')->index();
            $table->string('title_sort');
            $table->string('original_language')->nullable();
            $table->boolean('adult')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('budget')->nullable();
            $table->string('homepage')->nullable();
            $table->string('original_title')->nullable();
            $table->mediumText('overview')->nullable();
            $table->string('popularity')->nullable();
            $table->string('poster')->nullable();
            $table->date('release_date')->nullable();
            $table->string('revenue')->nullable();
            $table->string('runtime')->nullable();
            $table->string('status')->nullable();
            $table->string('tagline')->nullable();
            $table->string('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->timestamps();
        });
    }
};
