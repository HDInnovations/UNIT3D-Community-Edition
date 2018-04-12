<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Torrent;
use App\Peer;
use App\History;
use App\Category;
use App\Group;
use App\BonTransactions;
use App\TorrentRequest;
use Carbon\Carbon;

class StatsController extends Controller
{

    /**
     * Extra-Stats Manager
     *
     *
     * @access public
     * @return view::make stats.index
     */
    public function index()
    {
        // Site Stats Block

        // Total Members Count
        $num_user = cache()->remember('num_user', 60, function () {
            return User::all()->count();
        });
        // Total Torrents Count
        $num_torrent = cache()->remember('num_torrent', 60, function () {
            return Torrent::all()->count();
        });
        // Total Categories With Torrent Count
        $categories = Category::select('name', 'position', 'num_torrent')->oldest('position')->get();
        // Total HD Count
        $num_hd = cache()->remember('num_hd', 60, function () {
            return Torrent::where('sd', 0)->count();
        });
        // Total SD Count
        $num_sd = cache()->remember('num_sd', 60, function () {
            return Torrent::where('sd', 1)->count();
        });
        // Total Seeders
        $num_seeders = cache()->remember('num_seeders', 60, function () {
            return Peer::where('seeder', 1)->count();
        });
        // Total Leechers
        $num_leechers = cache()->remember('num_leechers', 60, function () {
            return Peer::where('seeder', 0)->count();
        });
        // Total Peers
        $num_peers = cache()->remember('num_peers', 60, function () {
            return Peer::all()->count();
        });
        //Total Upload Traffic Without Double Upload
        $actual_upload = cache()->remember('actual_upload', 60, function () {
            return History::all()->sum('actual_uploaded');
        });
        //Total Upload Traffic With Double Upload
        $credited_upload = cache()->remember('credited_upload', 60, function () {
            return History::all()->sum('uploaded');
        });
        //Total Download Traffic Without Freeleech
        $actual_download = cache()->remember('actual_download', 60, function () {
            return History::all()->sum('actual_downloaded');
        });
        //Total Download Traffic With Freeleech
        $credited_download = cache()->remember('credited_download', 60, function () {
            return History::all()->sum('downloaded');
        });
        $actual_up_down = $actual_upload + $actual_download;     //Total Up/Down Traffic without perks
        $credited_up_down = $credited_upload + $credited_download;     //Total Up/Down Traffic with perks

        return view('stats.index', ['num_user' => $num_user, 'num_torrent' => $num_torrent, 'categories' => $categories, 'num_hd' => $num_hd, 'num_sd' => $num_sd,
            'num_seeders' => $num_seeders, 'num_leechers' => $num_leechers, 'num_peers' => $num_peers,
            'actual_upload' => $actual_upload, 'actual_download' => $actual_download, 'actual_up_down' => $actual_up_down,
            'credited_upload' => $credited_upload, 'credited_download' => $credited_download, 'credited_up_down' => $credited_up_down,
        ]);
    }

    // USER CATEGORY
    public function uploaded()
    {
        // Fetch Top Uploaders
        $uploaded = User::latest('uploaded')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.uploaded', ['uploaded' => $uploaded]);
    }

    public function downloaded()
    {
        // Fetch Top Downloaders
        $downloaded = User::latest('downloaded')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.downloaded', ['downloaded' => $downloaded]);
    }

    public function seeders()
    {
        // Fetch Top Seeders
        $seeders = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', 1)->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.seeders', ['seeders' => $seeders]);
    }

    public function leechers()
    {
        // Fetch Top Leechers
        $leechers = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', 0)->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.leechers', ['leechers' => $leechers]);
    }

    public function uploaders()
    {
        // Fetch Top Uploaders
        $uploaders = Torrent::with('user')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.uploaders', ['uploaders' => $uploaders]);
    }

    public function bankers()
    {
        // Fetch Top Bankers
        $bankers = User::latest('seedbonus')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.bankers', ['bankers' => $bankers]);
    }

    public function seedtime()
    {
        // Fetch Top Total Seedtime
        $seedtime = User::with('history')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->sum('seedtime');

        return view('stats.users.seedtime', ['seedtime' => $seedtime]);
    }

    public function seedsize()
    {
        // Fetch Top Total Seedsize Users
        $seedsize = User::with('peers', 'torrents')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->sum('size');

        return view('stats.users.seedsize', ['seedsize' => $seedsize]);
    }

    //TORRENT CATEGORY
    public function seeded()
    {
        // Fetch Top Seeded
        $seeded = Torrent::latest('seeders')->take(100)->get();

        return view('stats.torrents.seeded', ['seeded' => $seeded]);
    }

    public function leeched()
    {
        // Fetch Top Leeched
        $leeched = Torrent::latest('leechers')->take(100)->get();

        return view('stats.torrents.leeched', ['leeched' => $leeched]);
    }

    public function completed()
    {
        // Fetch Top Completed
        $completed = Torrent::latest('times_completed')->take(100)->get();

        return view('stats.torrents.completed', ['completed' => $completed]);
    }

    public function dying()
    {
        // Fetch Top Dying
        $dying = Torrent::where('seeders', 1)->where('times_completed', '>=', '1')->latest('leechers')->take(100)->get();

        return view('stats.torrents.dying', ['dying' => $dying]);
    }

    public function dead()
    {
        // Fetch Top Dead
        $dead = Torrent::where('seeders', 0)->latest('leechers')->take(100)->get();

        return view('stats.torrents.dead', ['dead' => $dead]);
    }

    //REQUEST CATEGORY
    public function bountied()
    {
        // Fetch Top Bountied
        $bountied = TorrentRequest::latest('bounty')->take(100)->get();

        return view('stats.requests.bountied', ['bountied' => $bountied]);
    }

    //GROUPS CATEGORY
    public function groups()
    {
        // Fetch Groups User Counts
        $groups = Group::oldest('position')->get();

        return view('stats.groups.groups', ['groups' => $groups]);
    }

    public function group($id)
    {
        // Fetch Users In Group
        $group = Group::findOrFail($id);
        $users = User::where('group_id', $group->id)->latest()->paginate(100);

        return view('stats.groups.group', ['users' => $users, 'group' => $group]);
    }
}
