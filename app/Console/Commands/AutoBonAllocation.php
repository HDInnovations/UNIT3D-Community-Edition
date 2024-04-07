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
        $peersWithTorrents = DB::table('peers')
            ->select('peers.user_id', 
                     'torrents.id as torrent_id', 
                     'torrents.size', 
                     'torrents.seeders as seeder_count')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->whereRaw('date_sub(peers.created_at, interval 30 minute) < now()')
            ->get();

        $bonAllocations = [];

        foreach ($peersWithTorrents as $peerSeedingTorrent) {
            $totalBON = $this->calculateBonusForTorrent($peerSeedingTorrent->seeder_count, $peerSeedingTorrent->size, $byteUnits);
        
            // Accumulate bonuses per user
            if (isset($bonAllocations[$peerSeedingTorrent->user_id])) {
                $bonAllocations[$peerSeedingTorrent->user_id] += $totalBON;
            } else {
                $bonAllocations[$peerSeedingTorrent->user_id] = $totalBON;
            }
        }        

        //Move data from array to BonTransactions table
        /*foreach ($array as $key => $value) {
            $log = new BonTransactions();
            $log->bon_exchange_id = 0;
            $log->name = "Seeding Award";
            $log->cost = $value;
            $log->receiver_id = $key;
            $log->comment = "Seeding Award";
            $log->save();
        }*/

        //Move data from array to Users table
        foreach ($array as $key => $value) {
            User::whereKey($key)->update([
                'seedbonus' => DB::raw('seedbonus + '.$value),
            ]);
        }

        $this->comment('Automated BON Allocation Command Complete');
    }

    /**
     * Calculate bonus points for a single torrent based on seeder scarcity and size.
     *
     * @param int $seederCount The number of seeders for the torrent.
     * @param int $size The size of the torrent in bytes.
     * @param ByteUnits $byteUnits ByteUnits service for converting bytes to gigabytes.
     * @return float The calculated bonus points for the torrent.
     */
    protected function calculateBonusForTorrent(int $seederCount, int $size, $byteUnits): float
    {
        $seederBonus = $this->calculateSeederBonus($seederCount);
        // Ensure size is at least 1 GB for calculation, converting size to GB
        $sizeInGB = max(1, ceil($this->byteUnits->toGigabytes($size)));

        // Apply logarithmic scaling factor
        $scale = 0.5;
        $sizeMultiplier = log(max(1, $sizeInGB)) + 1; // Ensure a minimum value for multiplier
        $sizeMultiplier = $sizeMultiplier * $scale; // Adjust multiplier by scale

        return $seederBonus * $sizeMultiplier;
    }

    /**
     * Calculate the bonus points based on the number of seeders.
     */
    protected function calculateSeederBonus(int $seederCount): float
    {
        if ($seederCount === 1) {
            return 10.0;
        } elseif ($seederCount === 2) {
            return 5.0;
        } elseif ($seederCount >= 3 && $seederCount <= 5) {
            return 3.0;
        } elseif ($seederCount >= 6 && $seederCount <= 9) {
            return 2.0;
        } elseif ($seederCount >= 10 && $seederCount <= 19) {
            return 1.5;
        } elseif ($seederCount >= 20 && $seederCount <= 35) {
            return 1;
        } elseif ($seederCount >= 36 && $seederCount <= 49) {
            return 0.75;
        }

        return 0.5; // Base amount for 50 or more seeders
    }
}
