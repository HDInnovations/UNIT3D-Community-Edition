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

        DB::table('history')
            ->join('torrents', 'torrents.info_hash', '=', 'history.info_hash')
            ->update([
                'torrent_id' => DB::raw('torrents.id')
            ]);
    }
};
