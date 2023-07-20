<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table): void {
            $table->dropColumn('position');
            $table->index('name');
        });
    }
};
