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
        Schema::table('crew_movie', function (Blueprint $table): void {
            $table->dropPrimary(['movie_id', 'person_id']);
            $table->primary(['movie_id', 'person_id', 'department', 'job']);
        });
    }
};
