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

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Group;
use App\Models\History;
use App\Models\Language;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * StatsController Constructor.
     */
    public function __construct()
    {
        $this->expiresAt = Carbon::now()->addMinutes(10);
    }

    /**
     * Show Extra-Stats Index.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Total Members Count (All Groups)
        $all_user = cache()->remember('all_user', $this->expiresAt, function () {
            return User::withTrashed()->count();
        });

        // Total Active Members Count (Not Validating, Banned, Disabled, Pruned)
        $active_user = cache()->remember('active_user', $this->expiresAt, function () {
            $banned_group = cache()->rememberForever('banned_group', function () {
                return Group::where('slug', '=', 'banned')->pluck('id');
            });
            $validating_group = cache()->rememberForever('validating_group', function () {
                return Group::where('slug', '=', 'validating')->pluck('id');
            });
            $disabled_group = cache()->rememberForever('disabled_group', function () {
                return Group::where('slug', '=', 'disabled')->pluck('id');
            });
            $pruned_group = cache()->rememberForever('pruned_group', function () {
                return Group::where('slug', '=', 'pruned')->pluck('id');
            });

            return User::whereNotIn('group_id', [$validating_group[0], $banned_group[0], $disabled_group[0], $pruned_group[0]])->count();
        });

        // Total Disabled Members Count
        $disabled_user = cache()->remember('disabled_user', $this->expiresAt, function () {
            $disabled_group = cache()->rememberForever('disabled_group', function () {
                return Group::where('slug', '=', 'disabled')->pluck('id');
            });

            return User::where('group_id', '=', $disabled_group[0])->count();
        });

        // Total Pruned Members Count
        $pruned_user = cache()->remember('pruned_user', $this->expiresAt, function () {
            $pruned_group = cache()->rememberForever('pruned_group', function () {
                return Group::where('slug', '=', 'pruned')->pluck('id');
            });

            return User::onlyTrashed()->where('group_id', '=', $pruned_group[0])->count();
        });

        // Total Banned Members Count
        $banned_user = cache()->remember('banned_user', $this->expiresAt, function () {
            $banned_group = cache()->rememberForever('banned_group', function () {
                return Group::where('slug', '=', 'banned')->pluck('id');
            });

            return User::where('group_id', '=', $banned_group[0])->count();
        });

        // Total Torrents Count
        $num_torrent = cache()->remember('num_torrent', $this->expiresAt, function () {
            return Torrent::count();
        });

        // Total Categories With Torrent Count
        $categories = Category::withCount('torrents')->get()->sortBy('position');

        // Total HD Count
        $num_hd = cache()->remember('num_hd', $this->expiresAt, function () {
            return Torrent::where('sd', '=', 0)->count();
        });

        // Total SD Count
        $num_sd = cache()->remember('num_sd', $this->expiresAt, function () {
            return Torrent::where('sd', '=', 1)->count();
        });

        // Total Torrent Size
        $torrent_size = cache()->remember('torrent_size', $this->expiresAt, function () {
            return Torrent::sum('size');
        });

        // Total Seeders
        $num_seeders = cache()->remember('num_seeders', $this->expiresAt, function () {
            return Peer::where('seeder', '=', 1)->count();
        });

        // Total Leechers
        $num_leechers = cache()->remember('num_leechers', $this->expiresAt, function () {
            return Peer::where('seeder', '=', 0)->count();
        });

        // Total Peers
        $num_peers = cache()->remember('num_peers', $this->expiresAt, function () {
            return Peer::count();
        });

        //Total Upload Traffic Without Double Upload
        $actual_upload = cache()->remember('actual_upload', $this->expiresAt, function () {
            return History::sum('actual_uploaded');
        });

        //Total Upload Traffic With Double Upload
        $credited_upload = cache()->remember('credited_upload', $this->expiresAt, function () {
            return History::sum('uploaded');
        });

        //Total Download Traffic Without Freeleech
        $actual_download = cache()->remember('actual_download', $this->expiresAt, function () {
            return History::sum('actual_downloaded');
        });

        //Total Download Traffic With Freeleech
        $credited_download = cache()->remember('credited_download', $this->expiresAt, function () {
            return History::sum('downloaded');
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
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });
        $pruned_group = cache()->rememberForever('pruned_group', function () {
            return Group::where('slug', '=', 'pruned')->pluck('id');
        });

        // Fetch Top Uploaders
        $uploaded = User::latest('uploaded')->whereNotIn('group_id', [$validating_group[0], $banned_group[0], $disabled_group[0], $pruned_group[0]])->take(100)->get();

        return view('stats.users.uploaded', ['uploaded' => $uploaded]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloaded()
    {
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });
        $pruned_group = cache()->rememberForever('pruned_group', function () {
            return Group::where('slug', '=', 'pruned')->pluck('id');
        });

        // Fetch Top Downloaders
        $downloaded = User::latest('downloaded')->whereNotIn('group_id', [$validating_group[0], $banned_group[0], $disabled_group[0], $pruned_group[0]])->take(100)->get();

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
        $banned_group = cache()->rememberForever('banned_group', function () {
            return Group::where('slug', '=', 'banned')->pluck('id');
        });
        $validating_group = cache()->rememberForever('validating_group', function () {
            return Group::where('slug', '=', 'validating')->pluck('id');
        });
        $disabled_group = cache()->rememberForever('disabled_group', function () {
            return Group::where('slug', '=', 'disabled')->pluck('id');
        });
        $pruned_group = cache()->rememberForever('pruned_group', function () {
            return Group::where('slug', '=', 'pruned')->pluck('id');
        });

        // Fetch Top Bankers
        $bankers = User::latest('seedbonus')->whereNotIn('group_id', [$validating_group[0], $banned_group[0], $disabled_group[0], $pruned_group[0]])->take(100)->get();

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
     * @param $id
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

    /**
     * Show Extra-Stats Languages.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function languages()
    {
        // Fetch All Languages
        $languages = Language::allowed();

        return view('stats.languages.languages', ['languages' => $languages]);
    }
}
