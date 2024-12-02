<?php

declare(strict_types=1);

/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

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
        $duplicates = DB::table('history')
            ->select(
                'torrent_id',
                'user_id',
                DB::raw('COUNT(*) as "count"')
            )
            ->groupBy('torrent_id', 'user_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $records = DB::table('history')
                ->where('torrent_id', '=', $duplicate->torrent_id)
                ->where('user_id', '=', $duplicate->user_id)
                ->get();

            $merged = $records->first();

            DB::table('history')->where('id', '=', $merged->id)->update([
                'seedtime'          => $records->sum('seedtime'),
                'downloaded'        => $records->sum('downloaded'),
                'actual_downloaded' => $records->sum('actual_downloaded'),
                'uploaded'          => $records->sum('uploaded'),
                'actual_uploaded'   => $records->sum('actual_uploaded'),
            ]);

            DB::table('history')
                ->whereIn('id', $records->where('id', '!=', $merged->id)->pluck('id'))
                ->delete();
        }

        Schema::table('history', function (Blueprint $table): void {
            $table->unique(['user_id', 'torrent_id']);
        });
    }
};
