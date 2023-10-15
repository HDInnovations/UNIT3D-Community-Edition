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
        Schema::table('history', function (Blueprint $table): void {
            $table->unsignedBigInteger('torrent_id')->after('user_id')->index();
            $table->index(['user_id', 'torrent_id']);
        });

        foreach (DB::table('history')->get() as $history) {
            $torrent = DB::table('torrents')->where('info_hash', '=', $history->info_hash)->pluck('id');
            $history->torrent_id = $torrent[0];
            $history->save();
        }
    }
};
