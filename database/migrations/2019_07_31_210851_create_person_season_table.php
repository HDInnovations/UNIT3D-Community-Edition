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
        Schema::create('person_season', function (Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('season_id');
            $table->primary(['person_id', 'season_id']);
        });
    }
};
