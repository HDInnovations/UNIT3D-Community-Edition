<?php

use App\Models\History;
use App\Models\Torrent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history', function (Blueprint $table) {
            $table->unsignedBigInteger('torrent_id')->after('user_id')->index();
            $table->index(['user_id', 'torrent_id']);
        });

        foreach (History::all() as $history) {
            $torrent = Torrent::where('info_hash', '=', $history->info_hash)->pluck('id');
            $history->torrent_id = $torrent[0];
            $history->save();
        }
    }
};
