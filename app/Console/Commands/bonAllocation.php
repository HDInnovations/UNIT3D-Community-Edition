<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\BonTransactions;
use App\Peer;
use App\User;

class bonAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bonAllocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocates Bonus Points to users based on Peer Activity.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Dying Torrent
        $dying = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('torrents.seeders', 1)
            ->where('torrents.times_completed', '>', 2)
            ->where('peers.seeder', 1)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $legendary = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $old = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), Interval 6 month)')
            ->whereRaw('torrents.created_at > date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $huge = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824 * 100)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $large = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824 * 25)
            ->where('torrents.size', '<', 1073741824 * 100)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $regular = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.info_hash)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('torrents.size', '>=', 1073741824)
            ->where('torrents.size', '<', 1073741824 * 25)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $participaint = DB::table('history')
            ->select(DB::raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000)
            ->where('history.seedtime', '<', 2592000 * 2)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();


        $teamplayer = DB::table('history')
            ->select(DB::raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 2)
            ->where('history.seedtime', '<', 2592000 * 3)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $commited = DB::table('history')
            ->select(DB::raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 3)
            ->where('history.seedtime', '<', 2592000 * 6)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $mvp = DB::table('history')
            ->select(DB::raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 6)
            ->where('history.seedtime', '<', 2592000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $legendary = DB::table('history')
            ->select(DB::raw('count(DISTINCT(history.info_hash)) as value'), 'history.user_id')
            ->join('torrents', 'torrents.info_hash', 'history.info_hash')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2592000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        //Move data from SQL to array

        $array = [];

        foreach ($dying as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 2;
            } else {
                $array[$value->user_id] = $value->value * 2;
            }
        }

        foreach ($legendary as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1.5;
            } else {
                $array[$value->user_id] = $value->value * 1.5;
            }
        }

        foreach ($old as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($huge as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($large as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($regular as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($participaint as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($teamplayer as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($commited as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($mvp as $key => $value) {
            if (array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($legendary as $key => $value) {
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
            $user = User::where('id', $key)->first();
            $user->seedbonus += $value;
            $user->save();
        }
    }
}
