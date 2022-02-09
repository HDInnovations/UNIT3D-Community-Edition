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
        Schema::table('torrents', function (Blueprint $table) {
            $table->integer('season_number')->after('igdb')->nullable()->index();
            $table->integer('episode_number')->after('season_number')->nullable()->index();
        });
    }
};
