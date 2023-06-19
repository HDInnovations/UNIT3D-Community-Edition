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
    public \Illuminate\Support\Carbon $carbon;

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

        // Total Seeders
        $numSeeders = cache()->remember('num_seeders', $this->carbon, fn () => Peer::where('seeder', '=', 1)->count());

        // Total Leechers
        $numLeechers = cache()->remember('num_leechers', $this->carbon, fn () => Peer::where('seeder', '=', 0)->count());

        //Total Upload Traffic Without Double Upload
        $actualUpload = cache()->remember('actual_upload', $this->carbon, fn () => History::sum('actual_uploaded'));

        //Total Upload Traffic With Double Upload
        $creditedUpload = cache()->remember('credited_upload', $this->carbon, fn () => History::sum('uploaded'));

        //Total Download Traffic Without Freeleech
        $actualDownload = cache()->remember('actual_download', $this->carbon, fn () => History::sum('actual_downloaded'));

        //Total Download Traffic With Freeleech
        $creditedDownload = cache()->remember('credited_download', $this->carbon, fn () => History::sum('downloaded'));

        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
        $prunedGroup = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

        return view('stats.index', [
            'all_user' => cache()->remember(
                'all_user',
                $this->carbon,
                fn () => User::withTrashed()->count()
            ),
            'active_user' => cache()->remember(
                'active_user',
                $this->carbon,
                fn () => User::whereIntegerNotInRaw('group_id', [$validatingGroup[0], $bannedGroup[0], $disabledGroup[0], $prunedGroup[0]])->count()
            ),
            'disabled_user' => cache()->remember(
                'disabled_user',
                $this->carbon,
                fn () => User::where('group_id', '=', $disabledGroup[0])->count()
            ),
            'pruned_user' => cache()->remember(
                'pruned_user',
                $this->carbon,
                fn () => User::onlyTrashed()->where('group_id', '=', $prunedGroup[0])->count()
            ),
            'banned_user' => cache()->remember(
                'banned_user',
                $this->carbon,
                fn () => User::where('group_id', '=', $bannedGroup[0])->count()
            ),
            'num_torrent'       => $numTorrent,
            'categories'        => Category::withCount('torrents')->orderBy('position')->get(),
            'num_hd'            => $numTorrent - $numSd,
            'num_sd'            => $numSd,
            'torrent_size'      => cache()->remember('torrent_size', $this->carbon, fn () => Torrent::sum('size')),
            'num_seeders'       => $numSeeders,
            'num_leechers'      => $numLeechers,
            'num_peers'         => $numSeeders + $numLeechers,
            'actual_upload'     => $actualUpload,
            'actual_download'   => $actualDownload,
            'actual_up_down'    => $actualUpload + $actualDownload,
            'credited_upload'   => $creditedUpload,
            'credited_download' => $creditedDownload,
            'credited_up_down'  => $creditedUpload + $creditedDownload,
        ]);
    }

    /**
     * Show Extra-Stats Users.
     *
     * @throws Exception
     */
    public function uploaded(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
        $prunedGroup = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

        return view('stats.users.uploaded', [
            'uploaded' => User::orderByDesc('uploaded')
                ->whereIntegerNotInRaw('group_id', [$validatingGroup[0], $bannedGroup[0], $disabledGroup[0], $prunedGroup[0]])
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
        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
        $prunedGroup = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

        return view('stats.users.downloaded', [
            'downloaded' => User::orderByDesc('downloaded')
                ->whereIntegerNotInRaw('group_id', [$validatingGroup[0], $bannedGroup[0], $disabledGroup[0], $prunedGroup[0]])
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
        $bannedGroup = cache()->rememberForever('banned_group', fn () => Group::where('slug', '=', 'banned')->pluck('id'));
        $validatingGroup = cache()->rememberForever('validating_group', fn () => Group::where('slug', '=', 'validating')->pluck('id'));
        $disabledGroup = cache()->rememberForever('disabled_group', fn () => Group::where('slug', '=', 'disabled')->pluck('id'));
        $prunedGroup = cache()->rememberForever('pruned_group', fn () => Group::where('slug', '=', 'pruned')->pluck('id'));

        return view('stats.users.bankers', [
            'bankers' => User::orderByDesc('seedbonus')
                ->whereIntegerNotInRaw('group_id', [$validatingGroup[0], $bannedGroup[0], $disabledGroup[0], $prunedGroup[0]])
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
            'groups' => Group::orderBy('position')->get(),
        ]);
    }

    /**
     * Show Extra-Stats Groups.
     */
    public function group(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $group = Group::findOrFail($id);

        return view('stats.groups.group', [
            'users' => User::withTrashed()->where('group_id', '=', $group->id)->latest()->paginate(100),
            'group' => $group,
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
        $clients = [];

        if (cache()->has('stats:clients')) {
            $clients = cache()->get('stats:clients');
        }

        return view('stats.clients.clients', [
            'clients' => $clients,
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
