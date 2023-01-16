<?php

use App\Models\History;
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
        $duplicates = DB::table('history')
            ->select(
                'torrent_id',
                'user_id',
                DB::raw('COUNT(*) as `count`')
            )
            ->groupBy('torrent_id', 'user_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $records = History::query()
                ->where('torrent_id', '=', $duplicate->torrent_id)
                ->where('user_id', '=', $duplicate->user_id)
                ->get();

            $merged = $records->first();

            $merged->seedtime = $records->sum('seedtime');
            $merged->downloaded = $records->sum('downloaded');
            $merged->actual_downloaded = $records->sum('actual_downloaded');
            $merged->uploaded = $records->sum('uploaded');
            $merged->actual_uploaded = $records->sum('actual_uploaded');
            $merged->save();

            foreach ($records->where('id', '!=', $merged->id) as $record) {
                $record->delete();
            }
        }

        Schema::table('history', function (Blueprint $table) {
            $table->unique(['user_id', 'torrent_id']);
        });
    }
};
