<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bon_transactions', function (Blueprint $table): void {
            $table->unsignedInteger('sender')->nullable()->default(null)->change();
            $table->unsignedInteger('receiver')->nullable()->default(null)->change();
            $table->unsignedInteger('torrent_id')->nullable()->default(null)->change();
        });

        DB::table('bon_transactions')->where('sender', '=', 0)->update([
            'sender' => null,
        ]);

        DB::table('bon_transactions')->where('receiver', '=', 0)->update([
            'receiver' => null,
        ]);

        DB::table('bon_transactions')->where('torrent_id', '=', 0)->update([
            'torrent_id' => null,
        ]);
    }
};
