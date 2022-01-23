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
        Schema::create('episode_person', function (Blueprint $table) {
            $table->unsignedInteger('episode_id');
            $table->unsignedInteger('person_id');
            $table->primary(['episode_id', 'person_id']);
        });
    }
};
