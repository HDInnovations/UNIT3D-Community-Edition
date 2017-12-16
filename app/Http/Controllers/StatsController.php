<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\User;
use App\Torrent;
use App\Peer;
use App\History;
use App\BonTransactions;
use App\Requests;
use App\Group;

use Illuminate\Support\Facades\DB;

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
        $num_user = User::all()->count();     // Total Members Count
        $num_torrent = Torrent::all()->count();     // Total Torrents Count
        $num_movies = Torrent::where('category_id', '1')->count();      // Total Movies Count
        $num_hdtv = Torrent::where('category_id', '2')->count();      // Total HDTV Count
        $num_fan = Torrent::where('category_id', '3')->count();     // Total FANRES Count
        $num_sd = Torrent::where('sd', '1')->count();      // Total SD Count
        $num_seeders = Peer::where('seeder', '1')->count();     // Total Seeders
        $num_leechers = Peer::where('seeder', '0')->count();      // Total Leechers
        $num_peers = Peer::all()->count();      // Total Peers
        $tot_upload = History::all()->sum('uploaded');      //Total Upload Traffic
        $tot_download = History::all()->sum('downloaded');      //Total Download Traffic
        $tot_up_down = $tot_upload + $tot_download;     //Total Up/Down Traffic

        return view('stats.index', ['num_user' => $num_user, 'num_torrent' => $num_torrent, 'num_movies' => $num_movies, 'num_hdtv' => $num_hdtv, 'num_sd' => $num_sd, 'num_fan' => $num_fan,
            'num_seeders' => $num_seeders, 'num_leechers' => $num_leechers, 'num_peers' => $num_peers, 'tot_upload' => $tot_upload, 'tot_download' => $tot_download, 'tot_up_down' => $tot_up_down]);
    }

    // USER CATEGORY
    public function uploaded()
    {
        // Fetch Top Uploaders
        $uploaded = User::orderBy('uploaded', 'DESC')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.uploaded', ['uploaded' => $uploaded]);
    }

    public function downloaded()
    {
        // Fetch Top Downloaders
        $downloaded = User::orderBy('downloaded', 'DESC')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.downloaded', ['downloaded' => $downloaded]);
    }

    public function seeders()
    {
        // Fetch Top Seeders
        $seeders = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', '=', '1')->groupBy('user_id')->orderBy('value', 'DESC')->take(100)->get();

        return view('stats.users.seeders', ['seeders' => $seeders]);
    }

    public function leechers()
    {
        // Fetch Top Leechers
        $leechers = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', '=', '0')->groupBy('user_id')->orderBy('value', 'DESC')->take(100)->get();

        return view('stats.users.leechers', ['leechers' => $leechers]);
    }

    public function uploaders()
    {
        // Fetch Top Uploaders
        $uploaders = Torrent::with('user')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->orderBy('value', 'DESC')->take(100)->get();

        return view('stats.users.uploaders', ['uploaders' => $uploaders]);
    }

    public function bankers()
    {
        // Fetch Top Bankers
        $bankers = User::orderBy('seedbonus', 'DESC')->where('group_id', '!=', 1)->where('group_id', '!=', 5)->take(100)->get();

        return view('stats.users.bankers', ['bankers' => $bankers]);
    }

    public function seedtime()
    {
        // Fetch Top Total Seedtime
        $seedtime = User::with('history')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->orderBy('value', 'DESC')->take(100)->sum('seedtime');

        return view('stats.users.seedtime', ['seedtime' => $seedtime]);
    }

    public function seedsize()
    {
        // Fetch Top Total Seedsize Users
        $seedsize = User::with('peers', 'torrents')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->orderBy('value', 'DESC')->take(100)->sum('size');

        return view('stats.users.seedsize', ['seedsize' => $seedsize]);
    }

    //TORRENT CATEGORY
    public function seeded()
    {
        // Fetch Top Seeded
        $seeded = Torrent::orderBy('seeders', 'DESC')->take(100)->get();

        return view('stats.torrents.seeded', ['seeded' => $seeded]);
    }

    public function leeched()
    {
        // Fetch Top Leeched
        $leeched = Torrent::orderBy('leechers', 'DESC')->take(100)->get();

        return view('stats.torrents.leeched', ['leeched' => $leeched]);
    }

    public function completed()
    {
        // Fetch Top Completed
        $completed = Torrent::orderBy('times_completed', 'DESC')->take(100)->get();

        return view('stats.torrents.completed', ['completed' => $completed]);
    }

    public function dying()
    {
        // Fetch Top Dying
        $dying = Torrent::where('seeders', '=', '1')->where('times_completed', '>=', '1')->orderBy('leechers', 'DESC')->take(100)->get();

        return view('stats.torrents.dying', ['dying' => $dying]);
    }

    public function dead()
    {
        // Fetch Top Dead
        $dead = Torrent::where('seeders', '=', '0')->orderBy('leechers', 'DESC')->take(100)->get();

        return view('stats.torrents.dead', ['dead' => $dead]);
    }

    //REQUEST CATEGORY
    public function bountied()
    {
        // Fetch Top Bountied
        $bountied = Requests::orderBy('bounty', 'DESC')->take(100)->get();

        return view('stats.requests.bountied', ['bountied' => $bountied]);
    }

    //GROUPS CATEGORY
    public function groups()
    {
        // Fetch Groups User Counts
        $groups = Group::orderBy('position', 'asc')->get();

        return view('stats.groups.groups', ['groups' => $groups]);
    }

    public function group($id)
    {
        // Fetch Users In Group
        $group = Group::findOrFail($id);
        $users = User::where('group_id', '=', $group->id)->orderBy('created_at', 'DESC')->paginate(100);

        return view('stats.groups.group', ['users' => $users, 'group' => $group]);
    }
}
