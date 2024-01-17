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

namespace App\Console\Commands;

use App\Helpers\ByteUnits;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\AutoBonAllocationTest
 */
class AutoBonAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:bon_allocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocates Bonus Points To Users Based On Peer Activity.';

    /**
     * Execute the console command.
     */
    public function handle(ByteUnits $byteUnits): void
    {
        $peers = DB::table('peers')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->select('peers.user_id', 'peers.seeder', 'peers.active', 'peers.created_at', 'torrents.seeders', 'torrents.times_completed', 'torrents.created_at as torrent_created_at', 'torrents.size')
            ->get();

        $history = DB::table('history')
            ->select('history.user_id', 'history.active', 'history.seedtime')
            ->get();

        $bonuses = [];

        foreach ($peers as $peer) {
            $bonus = 0;
            if ($peer->seeder == 1 && $peer->active == 1 && $peer->created_at->diffInMinutes(now()) > 30) {
                if ($peer->seeders == 1 && $peer->times_completed > 2) {
                    $bonus = 2;
                } elseif ($peer->torrent_created_at < now()->subYear(1)) {
                    $bonus = 1.5;
                } elseif ($peer->torrent_created_at < now()->subMonths(6) && $peer->torrent_created_at > now()->subYear(1)) {
                    $bonus = 1;
                } elseif ($peer->size >= $byteUnits->bytesFromUnit('100GiB')) {
                    $bonus = 0.75;
                } elseif ($peer->size >= $byteUnits->bytesFromUnit('25GiB') && $peer->size < $byteUnits->bytesFromUnit('100GiB')) {
                    $bonus = 0.50;
                } elseif ($peer->size >= $byteUnits->bytesFromUnit('1GiB') && $peer->size < $byteUnits->bytesFromUnit('25GiB')) {
                    $bonus = 0.25;
                }
            }
            $bonuses[$peer->user_id] = ($bonuses[$peer->user_id] ?? 0) + $bonus;
        }

        foreach ($history as $record) {
            $bonus = 0;
            if ($record->active == 1) {
                if ($record->seedtime >= 2592000 && $record->seedtime < 2592000 * 2) {
                    $bonus = 0.25;
                } elseif ($record->seedtime >= 2592000 * 2 && $record->seedtime < 2592000 * 3) {
                    $bonus = 0.50;
                } elseif ($record->seedtime >= 2592000 * 3 && $record->seedtime < 2592000 * 6) {
                    $bonus = 0.75;
                } elseif ($record->seedtime >= 2592000 * 6 && $record->seedtime < 2592000 * 12) {
                    $bonus = 1;
                } elseif ($record->seedtime >= 2592000 * 12) {
                    $bonus = 2;
                }
            }
            $bonuses[$record->user_id] = ($bonuses[$record->user_id] ?? 0) + $bonus;
        }

        foreach ($bonuses as $userId => $bonus) {
            User::whereKey($userId)->increment('seedbonus', $bonus);
        }

        $this->comment('Automated BON Allocation Command Complete');
    }
}
