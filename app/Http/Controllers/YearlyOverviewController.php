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

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use App\Models\Thank;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class YearlyOverviewController extends Controller
{
    /**
     * Get All Overviews.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        // Site Years
        $siteYears = collect();

        for ($currentYear = (int) date('Y', strtotime('-1 year')); $currentYear >= date('Y', strtotime(config('other.birthdate'))); $currentYear--) {
            $siteYears->push($currentYear);
        }

        return view('stats.yearly_overviews.index', [
            'siteYears' => $siteYears,
        ]);
    }

    /**
     * Get A Year Overview.
     */
    public function show(int $year): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Year Validation
        $yearValid = checkdate(1, 1, $year) && \strlen((string) $year) <= 4 && date('Y', strtotime(config('other.birthdate'))) <= $year && $year <= date('Y');

        if (!$yearValid) {
            abort(404);
        }

        // Site Years
        $siteYears = collect();

        for ($currentYear = (int) date('Y', strtotime('-1 year')); $currentYear >= date('Y', strtotime(config('other.birthdate'))); $currentYear--) {
            $siteYears->push($currentYear);
        }

        // Top 10 Best Downloaded Movie
        $topMovies = Torrent::with(['category'])
            ->where('category_id', '=', 1)
            ->whereYear('created_at', '=', $year)
            ->latest('times_completed')
            ->take(10)
            ->get();

        // Bottom 5 Worst Downloaded Movie
        $bottomMovies = Torrent::with(['category'])
            ->where('category_id', '=', 1)
            ->whereYear('created_at', '=', $year)
            ->oldest('times_completed')
            ->take(5)
            ->get();

        // Top 10 Best Downloaded TV
        $topTv = Torrent::with(['category'])
            ->where('category_id', '=', 2)
            ->whereYear('created_at', '=', $year)
            ->latest('times_completed')
            ->distinct('tmdb')
            ->take(10)
            ->get();

        // Top 10 Best Downloaded TV
        $bottomTv = Torrent::with(['category'])
            ->where('category_id', '=', 2)
            ->whereYear('created_at', '=', $year)
            ->oldest('times_completed')
            ->distinct('tmdb')
            ->take(5)
            ->get();

        // Top 10 Best Downloaded FANRES
        $topFanres = Torrent::with(['category'])
            ->where('category_id', '=', 3)
            ->whereYear('created_at', '=', $year)
            ->latest('times_completed')
            ->distinct('tmdb')
            ->take(10)
            ->get();

        // Top 10 Best Downloaded FANRES
        $bottomFanres = Torrent::with(['category'])
            ->where('category_id', '=', 3)
            ->whereYear('created_at', '=', $year)
            ->oldest('times_completed')
            ->distinct('tmdb')
            ->take(5)
            ->get();

        // Top 10 Uploaders By Content
        $uploaders = Torrent::with('user')
            ->whereYear('created_at', '=', $year)
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get();

        // Top 10 Requesters
        $requesters = TorrentRequest::with('user')
            ->whereYear('created_at', '=', $year)
            ->where('user_id', '!=', 1)
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get();

        // Top 10 Request Fillers
        $fillers = TorrentRequest::with('filler')
            ->whereYear('created_at', '=', $year)
            ->where('filled_by', '!=', 1)
            ->select(DB::raw('filled_by, count(*) as value'))
            ->groupBy('filled_by')
            ->latest('value')
            ->take(10)
            ->get();

        // Top 10 Commenters
        $commenters = Comment::with('user')
            ->whereYear('created_at', '=', $year)
            ->where('user_id', '!=', 1)
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get();

        // Top 10 Thankers
        $thankers = Thank::with('user')
            ->whereYear('created_at', '=', $year)
            ->where('user_id', '!=', 1)
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get();

        // Top 10 Forum Users By Posts
        $forums = Post::with('user')
            ->whereYear('created_at', '=', $year)
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get();

        // New Users
        $newUsers = User::whereYear('created_at', '=', $year)
            ->count();

        // Uploads By Category
        $movieUploads = Torrent::where('category_id', '=', 1)
            ->whereYear('created_at', '=', $year)
            ->count();

        $tvUploads = Torrent::where('category_id', '=', 2)
            ->whereYear('created_at', '=', $year)
            ->count();

        $fanresUploads = Torrent::where('category_id', '=', 3)
            ->whereYear('created_at', '=', $year)
            ->count();

        $trailerUploads = Torrent::where('category_id', '=', 5)
            ->whereYear('created_at', '=', $year)
            ->count();

        // Total Uploads
        $totalUploads = Torrent::whereYear('created_at', '=', $year)
            ->count();

        // Total Downloads
        $totalDownloads = Torrent::whereYear('created_at', '=', $year)
            ->sum('times_completed');

        // Staff List
        $staffers = Group::query()
            ->with('users.group')
            ->where('is_modo', '=', 1)
            ->orWhere('is_admin', '=', 1)
            ->get()
            ->sortByDesc('position');

        return view('stats.yearly_overviews.show', [
            'topMovies'      => $topMovies,
            'bottomMovies'   => $bottomMovies,
            'topTv'          => $topTv,
            'bottomTv'       => $bottomTv,
            'topFanres'      => $topFanres,
            'bottomFanres'   => $bottomFanres,
            'uploaders'      => $uploaders,
            'forums'         => $forums,
            'requesters'     => $requesters,
            'fillers'        => $fillers,
            'commenters'     => $commenters,
            'thankers'       => $thankers,
            'newUsers'       => $newUsers,
            'movieUploads'   => $movieUploads,
            'tvUploads'      => $tvUploads,
            'fanresUploads'  => $fanresUploads,
            'trailerUploads' => $trailerUploads,
            'totalUploads'   => $totalUploads,
            'totalDownloads' => $totalDownloads,
            'staffers'       => $staffers,
            'siteYears'      => $siteYears,
            'year'           => $year,
        ]);
    }
}
