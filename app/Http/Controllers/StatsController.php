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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\StatsControllerTest
 */
class StatsController extends Controller
{
    public Carbon $carbon;

    /**
     * StatsController Constructor.
     */
    public function __construct()
    {
        $this->carbon = Carbon::now()->addMinutes(10);
    }

    /**
     * Show Extra-Stats Index.
     *
     * @throws Exception
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        // Total Torrents Count
        $numTorrent = cache()->remember('num_torrent', $this->carbon, fn () => Torrent::count());

        // Total SD Count
        $numSd = cache()->remember('num_sd', $this->carbon, fn () => Torrent::where('sd', '=', 1)->count());

        // Generally sites have more seeders than leechers, so it ends up being faster (by approximately 50%) to compute these stats instead of computing them individually
        $leecherCount = cache()->remember('peer_seeder_count', $this->carbon, fn () => Peer::where('seeder', '=', false)->where('active', '=', true)->count());
        $peerCount = cache()->remember('peer_count', $this->carbon, fn () => Peer::where('active', '=', true)->count());

        $historyStats = cache()->remember(
            'history_stats',
            $this->carbon,
            fn () => History::query()
                ->selectRaw('SUM(actual_uploaded) as actual_upload')
                ->selectRaw('SUM(uploaded) as credited_upload')
                ->selectRaw('SUM(actual_downloaded) as actual_download')
                ->selectRaw('SUM(downloaded) as credited_download')
                ->first()
        );

        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));

        return view('stats.index', [
            'all_user' => cache()->remember(
                'all_user',
                $this->carbon,
                fn () => User::withTrashed()->count()
            ),
            'active_user' => cache()->remember(
                'active_user',
                $this->carbon,
                fn () => User::whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))->count()
            ),
            'disabled_user' => cache()->remember(
                'disabled_user',
                $this->carbon,
                fn () => User::whereIn('group_id', Group::select('id')->where('slug', '=', 'disabled'))->count()
            ),
            'pruned_user' => cache()->remember(
                'pruned_user',
                $this->carbon,
                fn () => User::onlyTrashed()->whereIn('group_id', Group::select('id')->where('slug', '=', 'pruned'))->count()
            ),
            'banned_user' => cache()->remember(
                'banned_user',
                $this->carbon,
                fn () => User::whereIn('group_id', Group::select('id')->where('slug', '=', 'banned'))->count()
            ),
            'num_torrent'       => $numTorrent,
            'categories'        => Category::withCount('torrents')->orderBy('position')->get(),
            'num_hd'            => $numTorrent - $numSd,
            'num_sd'            => $numSd,
            'torrent_size'      => cache()->remember('torrent_size', $this->carbon, fn () => Torrent::sum('size')),
            'num_seeders'       => $peerCount - $leecherCount,
            'num_leechers'      => $leecherCount,
            'num_peers'         => $peerCount,
            'actual_upload'     => $historyStats->actual_upload,
            'actual_download'   => $historyStats->actual_download,
            'actual_up_down'    => $historyStats->actual_upload + $historyStats->actual_download,
            'credited_upload'   => $historyStats->credited_upload,
            'credited_download' => $historyStats->credited_download,
            'credited_up_down'  => $historyStats->credited_upload + $historyStats->credited_download,
        ]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @throws Exception
     */
    public function uploaded(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.uploaded', [
            'uploaded' => User::orderByDesc('uploaded')
                ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @throws Exception
     */
    public function downloaded(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.downloaded', [
            'downloaded' => User::orderByDesc('downloaded')
                ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     */
    public function seeders(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.seeders', [
            'seeders' => Peer::with('user')
                ->select(DB::raw('user_id, count(distinct torrent_id) as value'))
                ->where('seeder', '=', 1)
                ->where('active', '=', 1)
                ->groupBy('user_id')
                ->orderByDesc('value')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     */
    public function leechers(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.leechers', [
            'leechers' => Peer::with('user')
                ->select(DB::raw('user_id, count(*) as value'))
                ->where('seeder', '=', 0)
                ->where('active', '=', 1)
                ->groupBy('user_id')
                ->orderByDesc('value')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     */
    public function uploaders(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.uploaders', [
            'uploaders' => Torrent::with('user')
                ->where('anon', '=', 0)
                ->select(DB::raw('user_id, count(*) as value'))
                ->groupBy('user_id')
                ->orderByDesc('value')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @throws Exception
     */
    public function bankers(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.bankers', [
            'bankers' => User::orderByDesc('seedbonus')
                ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     */
    public function seedtime(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.seedtime', [
            'users' => User::withSum('history as seedtime', 'seedtime')
                ->orderByDesc('seedtime')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Users.
     */
    public function seedsize(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.users.seedsize', [
            'users' => User::withSum('seedingTorrents as seedsize', 'size')
                ->orderByDesc('seedsize')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrents.
     */
    public function seeded(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.torrents.seeded', [
            'seeded' => Torrent::orderByDesc('seeders')->take(100)->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrents.
     */
    public function leeched(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.torrents.leeched', [
            'leeched' => Torrent::orderByDesc('leechers')->take(100)->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrents.
     */
    public function completed(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.torrents.completed', [
            'completed' => Torrent::orderByDesc('times_completed')->take(100)->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrents.
     */
    public function dying(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.torrents.dying', [
            'dying' => Torrent::where('seeders', '=', 1)
                ->where('times_completed', '>=', '1')
                ->orderByDesc('leechers')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrents.
     */
    public function dead(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.torrents.dead', [
            'dead' => Torrent::where('seeders', '=', 0)
                ->orderByDesc('leechers')
                ->take(100)
                ->get(),
        ]);
    }

    /**
     * Show Extra-Stats Torrent Requests.
     */
    public function bountied(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.requests.bountied', [
            'bountied' => TorrentRequest::orderByDesc('bounty')->take(100)->get(),
        ]);
    }

    /**
     * Show Extra-Stats Groups.
     */
    public function groups(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.groups.groups', [
            'groups' => Group::orderBy('position')->withCount(['users' => fn ($query) => $query->withTrashed()])->get(),
        ]);
    }

    /**
     * Show Extra-Stats Groups.
     */
    public function group(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.groups.group', [
            'group' => Group::findOrFail($id),
            'users' => User::with(['group'])->withTrashed()->where('group_id', '=', $id)->latest()->paginate(100),
        ]);
    }

    /**
     * Show Extra-Stats Languages.
     */
    public function languages(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.languages.languages', [
            'languages' => Language::allowed(),
        ]);
    }

    /**
     * Show Extra-Stats Clients.
     */
    public function clients(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.clients.clients', [
            'clients' => cache()->get('stats:clients') ?? [],
        ]);
    }

    /**
     * Show Extra-Stats Themes.
     */
    public function themes(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('stats.themes.index', [
            'siteThemes' => User::select(DB::raw('style, count(*) as value'))
                ->groupBy('style')
                ->orderByDesc('value')
                ->get(),
            'customThemes' => User::where('custom_css', '!=', '')
                ->select(DB::raw('custom_css, count(*) as value'))
                ->groupBy('custom_css')
                ->orderByDesc('value')
                ->get(),
            'standaloneThemes' => User::whereNotNull('standalone_css')
                ->select(DB::raw('standalone_css, count(*) as value'))
                ->groupBy('standalone_css')
                ->orderByDesc('value')
                ->get(),
        ]);
    }
}
