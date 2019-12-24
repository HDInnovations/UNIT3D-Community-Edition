<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use App\Models\BonTransactions;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\DatabaseManager;

final class AutoBonAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'auto:bon_allocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Allocates Bonus Points To Users Based On Peer Activity.';
    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $dying_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('torrents.seeders', 1)
            ->where('torrents.times_completed', '>', 2)
            ->where('peers.seeder', 1)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $legendary_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $old_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), Interval 6 month)')
            ->whereRaw('torrents.created_at > date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $huge_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1_073_741_824 * 100)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $large_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1_073_741_824 * 25)
            ->where('torrents.size', '<', 1_073_741_824 * 100)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $regular_torrent = $this->databaseManager->table('peers')
            ->select($this->databaseManager->raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1_073_741_824)
            ->where('torrents.size', '<', 1_073_741_824 * 25)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $participaint_seeder = $this->databaseManager->table('history')
            ->select($this->databaseManager->raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000)
            ->where('history.seedtime', '<', 2_592_000 * 2)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $teamplayer_seeder = $this->databaseManager->table('history')
            ->select($this->databaseManager->raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 2)
            ->where('history.seedtime', '<', 2_592_000 * 3)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $commited_seeder = $this->databaseManager->table('history')
            ->select($this->databaseManager->raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 3)
            ->where('history.seedtime', '<', 2_592_000 * 6)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $mvp_seeder = $this->databaseManager->table('history')
            ->select($this->databaseManager->raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 6)
            ->where('history.seedtime', '<', 2_592_000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $legendary_seeder = $this->databaseManager->table('history')
            ->select($this->databaseManager->raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        //Move data from SQL to array

        $array = [];

        foreach ($dying_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 2;
            } else {
                $array[$value->user_id] = $value->value * 2;
            }
        }

        foreach ($legendary_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1.5;
            } else {
                $array[$value->user_id] = $value->value * 1.5;
            }
        }

        foreach ($old_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($huge_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($large_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($regular_torrent as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($participaint_seeder as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($teamplayer_seeder as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($commited_seeder as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($mvp_seeder as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($legendary_seeder as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 2;
            } else {
                $array[$value->user_id] = $value->value * 2;
            }
        }

        //Move data from array to BonTransactions table
        /*foreach ($array as $key => $value) {
            $log = new BonTransactions();
            $log->itemID = 0;
            $log->name = "Seeding Award";
            $log->cost = $value;
            $log->receiver = $key;
            $log->comment = "Seeding Award";
            $log->save();
        }*/

        //Move data from array to Users table
        foreach ($array as $key => $value) {
            $user = User::where('id', '=', $key)->first();
            $user->seedbonus += $value;
            $user->save();
        }
    }
}
