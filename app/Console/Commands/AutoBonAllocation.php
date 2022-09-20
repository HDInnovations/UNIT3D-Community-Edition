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
use App\Models\BonEarning;
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
        $userEarnings = [];

        foreach (BonEarning::with('conditions')->orderBy('position')->get() as $bonEarning) {
            $query = DB::table('peers')
                ->select([
                    DB::raw('1 as `1`'),
                    DB::raw('TIMESTAMPDIFF(SECOND, torrents.created_at, NOW()) as age'),
                    'torrents.size',
                    'torrents.seeders',
                    'torrents.leechers',
                    'torrents.times_completed',
                    'history.seedtime',
                    'torrents.personal_release',
                    'torrents.internal',
                    DB::raw('max(peers.connectable) as connectable'),
                    'peers.torrent_id',
                    'peers.user_id',
                ])
                ->join('history', fn ($join) => $join->on('history.torrent_id', '=', 'peers.torrent_id')->where('history.user_id', '=', 'peers.user_id'))
                ->join('torrents', 'peers.torrent_id', '=', 'torrents.id')
                ->where('peers.seeder', '=', true)
                ->where('peers.active', '=', true)
                ->groupBy(['peers.torrent_id', 'peers.user_id']);

            foreach ($bonEarning->conditions as $condition) {
                // Validate raw values
                if (\in_array($condition->operand1, [
                    '1',
                    'age',
                    'size',
                    'seeders',
                    'leechers',
                    'times_completed',
                    'seedtime',
                    'personal_release',
                    'internal',
                    'connectable',
                ], true)) {
                    $query->having(DB::raw('`'.$condition->operand1.'`'), $condition->operator, $condition->operand2);
                }
            }

            switch ($bonEarning->operation) {
                case 'append':
                    foreach ($query->get() as $peer) {
                        @$userEarnings[$peer->user_id][$peer->torrent_id] += $peer->{$bonEarning->variable} * $bonEarning->multiplier;
                    }

                    break;
                case 'multiply':
                    foreach ($query->get() as $peer) {
                        @$userEarnings[$peer->user_id][$peer->torrent_id] *= $peer->{$bonEarning->variable} * $bonEarning->multiplier;
                    }

                    break;
            }
        }

        foreach ($userEarnings as $userId => $userEarning) {
            DB::table('users')
                ->where('id', '=', $userId)
                ->update([
                    'seedbonus' => 'seedbonus + '.array_sum($userEarning),
                ]);
        }

        $this->comment('Automated BON Allocation Command Complete');
    }
}
