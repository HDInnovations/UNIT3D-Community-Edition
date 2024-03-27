<?php
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
            $records = DB::table('peers')
                ->where('torrent_id', '=', $duplicate->torrent_id)
                ->where('user_id', '=', $duplicate->user_id)
                ->where('peer_id', '=', $duplicate->peer_id)
                ->get();

            $first = $records->first();

            foreach ($records->where('id', '!=', $first->id) as $record) {
                $record->delete();
            }
        }

        Schema::table('peers', function (Blueprint $table): void {
            $table->unique(['user_id', 'torrent_id', 'peer_id']);
        });
    }
};
