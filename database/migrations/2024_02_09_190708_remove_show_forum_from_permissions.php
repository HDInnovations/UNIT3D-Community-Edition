<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        DB::table('permissions')->update([
            'read_topic' => DB::raw('LEAST(read_topic, show_forum)'),
        ]);

        Schema::table('permissions', function (Blueprint $table): void {
            $table->dropColumn('show_forum');
        });
    }
};
