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
        Schema::table('rss', function (Blueprint $table): void {
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
