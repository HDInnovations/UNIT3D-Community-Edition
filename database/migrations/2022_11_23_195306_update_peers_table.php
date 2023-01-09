<?php

use App\Models\Peer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicates = DB::table('peers')
            ->select(
                'torrent_id',
                'user_id',
                'peer_id',
                DB::raw('COUNT(*) as `count`')
            )
            ->groupBy('torrent_id', 'user_id', 'peer_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $records = Peer::query()
                ->where('torrent_id', '=', $duplicate->torrent_id)
                ->where('user_id', '=', $duplicate->user_id)
                ->where('peer_id', '=', $duplicate->peer_id)
                ->get();

            $first = $records->first();

            foreach ($records->where('id', '!=', $first->id) as $record) {
                $record->delete();
            }
        }

        Schema::table('peers', function (Blueprint $table) {
            $table->unique(['user_id', 'torrent_id', 'peer_id']);
        });
    }
};
