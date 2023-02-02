<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('torrents', function (Blueprint $table): void {
            $table->dateTime('fl_until')->after('bumped_at')->nullable();
            $table->dateTime('du_until')->after('fl_until')->nullable();
            $table->index(['fl_until', 'du_until']);
        });
    }
};
