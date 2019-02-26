<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Peer;
use App\Models\User;
use App\Models\Group;
use App\Models\History;
use App\Models\Torrent;
use App\Models\Category;
use App\Models\TorrentRequest;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Show Extra-Stats Index.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Extend The Maximum Execution Time
        set_time_limit(300);

        // Total Members Count (All Groups)
        $all_user = cache()->remember('all_user', 3600, function () {
            return User::withTrashed()->count();
        });

        // Total Active Members Count (Not Validating, Banned, Disabled, Pruned)
        $active_user = cache()->remember('active_user', 3600, function () {
            $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();
            $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
            $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();
            $prunedGroup = Group::where('slug', '=', 'pruned')->select('id')->first();

            return User::whereNotIn('group_id', [$validatingGroup->id, $bannedGroup->id, $disabledGroup->id, $prunedGroup->id])->count();
        });

        // Total Disabled Members Count
        $disabled_user = cache()->remember('disabled_user', 3600, function () {
            $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();

            return User::where('group_id', '=', $disabledGroup->id)->count();
        });

        // Total Pruned Members Count
        $pruned_user = cache()->remember('pruned_user', 3600, function () {
            $prunedGroup = Group::where('slug', '=', 'pruned')->select('id')->first();

            return User::onlyTrashed()->where('group_id', '=', $prunedGroup->id)->count();
        });

        // Total Banned Members Count
        $banned_user = cache()->remember('banned_user', 3600, function () {
            $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();

            return User::where('group_id', '=', $bannedGroup->id)->count();
        });

        // Total Torrents Count
        $num_torrent = cache()->remember('num_torrent', 3600, function () {
            return Torrent::count();
        });

        // Total Categories With Torrent Count
        $categories = Category::withCount('torrents')->get()->sortBy('position');

        // Total HD Count
        $num_hd = cache()->remember('num_hd', 3600, function () {
            return Torrent::where('sd', '=', 0)->count();
        });

        // Total SD Count
        $num_sd = cache()->remember('num_sd', 3600, function () {
            return Torrent::where('sd', '=', 1)->count();
        });

        // Total Torrent Size
        $torrent_size = cache()->remember('torrent_size', 3600, function () {
            return Torrent::select(['size'])->sum('size');
        });

        // Total Seeders
        $num_seeders = cache()->remember('num_seeders', 3600, function () {
            return Peer::where('seeder', '=', 1)->count();
        });

        // Total Leechers
        $num_leechers = cache()->remember('num_leechers', 3600, function () {
            return Peer::where('seeder', '=', 0)->count();
        });

        // Total Peers
        $num_peers = cache()->remember('num_peers', 3600, function () {
            return Peer::count();
        });

        //Total Upload Traffic Without Double Upload
        $actual_upload = cache()->remember('actual_upload', 3600, function () {
            return History::all()->sum('actual_uploaded');
        });

        //Total Upload Traffic With Double Upload
        $credited_upload = cache()->remember('credited_upload', 3600, function () {
            return History::all()->sum('uploaded');
        });

        //Total Download Traffic Without Freeleech
        $actual_download = cache()->remember('actual_download', 3600, function () {
            return History::all()->sum('actual_downloaded');
        });

        //Total Download Traffic With Freeleech
        $credited_download = cache()->remember('credited_download', 3600, function () {
            return History::all()->sum('downloaded');
        });

        //Total Up/Down Traffic without perks
        $actual_up_down = $actual_upload + $actual_download;

        //Total Up/Down Traffic with perks
        $credited_up_down = $credited_upload + $credited_download;

        return view('stats.index', [
            'all_user'          => $all_user,
            'active_user'       => $active_user,
            'disabled_user'     => $disabled_user,
            'pruned_user'       => $pruned_user,
            'banned_user'       => $banned_user,
            'num_torrent'       => $num_torrent,
            'categories'        => $categories,
            'num_hd'            => $num_hd,
            'num_sd'            => $num_sd,
            'torrent_size'      => $torrent_size,
            'num_seeders'       => $num_seeders,
            'num_leechers'      => $num_leechers,
            'num_peers'         => $num_peers,
            'actual_upload'     => $actual_upload,
            'actual_download'   => $actual_download,
            'actual_up_down'    => $actual_up_down,
            'credited_upload'   => $credited_upload,
            'credited_download' => $credited_download,
            'credited_up_down'  => $credited_up_down,
        ]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploaded()
    {
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();
        $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
        $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();
        $prunedGroup = Group::where('slug', '=', 'pruned')->select('id')->first();

        // Fetch Top Uploaders
        $uploaded = User::latest('uploaded')->whereNotIn('group_id', [$validatingGroup->id, $bannedGroup->id, $disabledGroup->id, $prunedGroup->id])->take(100)->get();

        return view('stats.users.uploaded', ['uploaded' => $uploaded]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloaded()
    {
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();
        $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
        $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();
        $prunedGroup = Group::where('slug', '=', 'pruned')->select('id')->first();

        // Fetch Top Downloaders
        $downloaded = User::latest('downloaded')->whereNotIn('group_id', [$validatingGroup->id, $bannedGroup->id, $disabledGroup->id, $prunedGroup->id])->take(100)->get();

        return view('stats.users.downloaded', ['downloaded' => $downloaded]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeders()
    {
        // Fetch Top Seeders
        $seeders = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', '=', 1)->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.seeders', ['seeders' => $seeders]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leechers()
    {
        // Fetch Top Leechers
        $leechers = Peer::with('user')->select(DB::raw('user_id, count(*) as value'))->where('seeder', '=', 0)->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.leechers', ['leechers' => $leechers]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploaders()
    {
        // Fetch Top Uploaders
        $uploaders = Torrent::with('user')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->get();

        return view('stats.users.uploaders', ['uploaders' => $uploaders]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankers()
    {
        $validatingGroup = Group::where('slug', '=', 'validating')->select('id')->first();
        $bannedGroup = Group::where('slug', '=', 'banned')->select('id')->first();
        $disabledGroup = Group::where('slug', '=', 'disabled')->select('id')->first();
        $prunedGroup = Group::where('slug', '=', 'pruned')->select('id')->first();

        // Fetch Top Bankers
        $bankers = User::latest('seedbonus')->whereNotIn('group_id', [$validatingGroup->id, $bannedGroup->id, $disabledGroup->id, $prunedGroup->id])->take(100)->get();

        return view('stats.users.bankers', ['bankers' => $bankers]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seedtime()
    {
        // Fetch Top Total Seedtime
        $seedtime = User::with('history')->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->sum('seedtime');

        return view('stats.users.seedtime', ['seedtime' => $seedtime]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seedsize()
    {
        // Fetch Top Total Seedsize Users
        $seedsize = User::with(['peers', 'torrents'])->select(DB::raw('user_id, count(*) as value'))->groupBy('user_id')->latest('value')->take(100)->sum('size');

        return view('stats.users.seedsize', ['seedsize' => $seedsize]);
    }

    /**
     * Show Extra-Stats Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeded()
    {
        // Fetch Top Seeded
        $seeded = Torrent::latest('seeders')->take(100)->get();

        return view('stats.torrents.seeded', ['seeded' => $seeded]);
    }

    /**
     * Show Extra-Stats Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leeched()
    {
        // Fetch Top Leeched
        $leeched = Torrent::latest('leechers')->take(100)->get();

        return view('stats.torrents.leeched', ['leeched' => $leeched]);
    }

    /**
     * Show Extra-Stats Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function completed()
    {
        // Fetch Top Completed
        $completed = Torrent::latest('times_completed')->take(100)->get();

        return view('stats.torrents.completed', ['completed' => $completed]);
    }

    /**
     * Show Extra-Stats Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dying()
    {
        // Fetch Top Dying
        $dying = Torrent::where('seeders', '=', 1)->where('times_completed', '>=', '1')->latest('leechers')->take(100)->get();

        return view('stats.torrents.dying', ['dying' => $dying]);
    }

    /**
     * Show Extra-Stats Torrents.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dead()
    {
        // Fetch Top Dead
        $dead = Torrent::where('seeders', '=', 0)->latest('leechers')->take(100)->get();

        return view('stats.torrents.dead', ['dead' => $dead]);
    }

    /**
     * Show Extra-Stats Torrent Requests.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bountied()
    {
        // Fetch Top Bountied
        $bountied = TorrentRequest::latest('bounty')->take(100)->get();

        return view('stats.requests.bountied', ['bountied' => $bountied]);
    }

    /**
     * Show Extra-Stats Groups.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function groups()
    {
        // Fetch Groups User Counts
        $groups = Group::oldest('position')->get();

        return view('stats.groups.groups', ['groups' => $groups]);
    }

    /**
     * Show Extra-Stats Groups.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function group($id)
    {
        // Fetch Users In Group
        $group = Group::findOrFail($id);
        $users = User::withTrashed()->where('group_id', '=', $group->id)->latest()->paginate(100);

        return view('stats.groups.group', ['users' => $users, 'group' => $group]);
    }
}
