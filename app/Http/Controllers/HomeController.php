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

use Carbon\Carbon;
use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Topic;
use App\Models\Article;
use App\Models\Torrent;
use Illuminate\Http\Request;
use App\Models\FeaturedTorrent;
use App\Models\PersonalFreeleech;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Home Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function home(Request $request)
    {
        // For Cache
        $current = Carbon::now();
        $expiresAt = $current->addMinutes(10);

        // Authorized User
        $user = $request->user();

        // Latest Articles/News Block
        $articles = cache()->remember('latest_article', $expiresAt, function () {
            return Article::latest()->take(1)->get();
        });

        // Latest Torrents Block
        $personal_freeleech = PersonalFreeleech::where('user_id', '=', $user->id)->first();

        $newest = cache()->remember('newest_torrents', $expiresAt, function () {
            return Torrent::with(['user', 'category'])
                ->withCount(['thanks', 'comments'])
                ->latest()
                ->take(5)
                ->get();
        });

        $seeded = cache()->remember('seeded_torrents', $expiresAt, function () {
            return Torrent::with(['user', 'category'])
                ->withCount(['thanks', 'comments'])
                ->latest('seeders')
                ->take(5)
                ->get();
        });

        $leeched = cache()->remember('leeched_torrents', $expiresAt, function () {
            return Torrent::with(['user', 'category'])
                ->withCount(['thanks', 'comments'])
                ->latest('leechers')
                ->take(5)
                ->get();
        });

        $dying = cache()->remember('dying_torrents', $expiresAt, function () {
            return Torrent::with(['user', 'category'])
                ->withCount(['thanks', 'comments'])
                ->where('seeders', '=', 1)
                ->where('times_completed', '>=', 1)
                ->latest('leechers')
                ->take(5)
                ->get();
        });

        $dead = cache()->remember('dead_torrents', $expiresAt, function () {
            return Torrent::with(['user', 'category'])
                ->withCount(['thanks', 'comments'])
                ->where('seeders', '=', 0)
                ->latest('leechers')
                ->take(5)
                ->get();
        });

        // Latest Topics Block
        $topics = cache()->remember('latest_topics', $expiresAt, function () {
            return Topic::latest()->take(5)->get();
        });

        // Latest Posts Block
        $posts = cache()->remember('latest_posts', $expiresAt, function () {
            return Post::latest()->take(5)->get();
        });

        // Online Block
        $users = new User;

        $groups = cache()->remember('user-groups', $expiresAt, function () {
            return Group::select(['name', 'color', 'effect', 'icon'])->oldest('position')->get();
        });

        // Featured Torrents Block
        $featured = cache()->remember('latest_featured', $expiresAt, function () {
            return FeaturedTorrent::with('torrent')->get();
        });

        // Latest Poll Block
        $poll = cache()->remember('latest_poll', $expiresAt, function () {
            return Poll::latest()->first();
        });

        // Top Uploaders Block
        $uploaders = cache()->remember('top_uploaders', $expiresAt, function () {
            return Torrent::with('user')
                ->select(DB::raw('user_id, count(*) as value'))
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get();
        });
        $past_uploaders = cache()->remember('month_uploaders', $expiresAt, function () use($current) {
            return Torrent::with('user')
                ->where('created_at', '>', $current->copy()->subDays(30)->toDateTimeString())
                ->select(DB::raw('user_id, count(*) as value'))
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get();
        });

        // Random Torrents
        $movies = DB::select("SELECT tmdb, imdb, category_id
                    FROM torrents 
                    AS r1 JOIN
                    (SELECT CEIL(RAND() *
                    (SELECT MAX(id)
                    FROM torrents)) AS id)
                    AS r2
                    WHERE r1.id >= r2.id
                    AND category_id =  1 
                    AND tmdb !=  0
                    ORDER BY r1.id ASC
                    LIMIT 3");

        $fanress = DB::select("SELECT tmdb, imdb, category_id
                    FROM torrents 
                    AS r1 JOIN
                    (SELECT CEIL(RAND() *
                    (SELECT MAX(id)
                    FROM torrents)) AS id)
                    AS r2
                    WHERE r1.id >= r2.id
                    AND category_id =  3 
                    AND tmdb !=  0
                    ORDER BY r1.id ASC
                    LIMIT 3");

        $tvs = DB::select("SELECT tmdb, imdb, category_id
                    FROM torrents 
                    AS r1 JOIN
                    (SELECT CEIL(RAND() *
                    (SELECT MAX(id)
                    FROM torrents)) AS id)
                    AS r2
                    WHERE r1.id >= r2.id
                    AND category_id =  2 
                    AND tmdb !=  0
                    ORDER BY r1.id ASC
                    LIMIT 3");

        return view('home.home', [
            'user'               => $user,
            'personal_freeleech' => $personal_freeleech,
            'users'              => $users,
            'groups'             => $groups,
            'articles'           => $articles,
            'newest'             => $newest,
            'seeded'             => $seeded,
            'dying'              => $dying,
            'leeched'            => $leeched,
            'dead'               => $dead,
            'topics'             => $topics,
            'posts'              => $posts,
            'featured'           => $featured,
            'poll'               => $poll,
            'uploaders'          => $uploaders,
            'past_uploaders'     => $past_uploaders,
            'movies'             => $movies,
            'fanress'            => $fanress,
            'tvs'                => $tvs,
        ]);
    }
}
