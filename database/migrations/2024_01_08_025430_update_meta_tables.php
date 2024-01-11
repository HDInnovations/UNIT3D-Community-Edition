<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table): void {
            $table->string('trailer')->nullable();
        });

        Schema::table('tv', function (Blueprint $table): void {
            $table->string('trailer')->nullable();
        });
    }
};
