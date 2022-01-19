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
        Schema::create('crew_movie', function (Blueprint $table) {
            $table->unsignedInteger('movie_id');
            $table->unsignedInteger('person_id');
            $table->primary(['movie_id', 'person_id']);
        });
    }
};
