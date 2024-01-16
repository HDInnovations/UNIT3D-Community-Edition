<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('twostep_auth');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('twostep');
        });
    }
};
