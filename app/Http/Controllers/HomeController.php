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

use App\Models\Article;
use App\Models\Bookmark;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\PersonalFreeleech;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display Home Page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // For Cache
        $current = Carbon::now();
        $expiresAt = $current->addMinutes(1);

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
            return Topic::with('forum')->latest()->take(5)->get();
        });

        // Latest Posts Block
        $posts = cache()->remember('latest_posts', $expiresAt, function () {
            return Post::with('topic', 'user')->latest()->take(5)->get();
        });

        // Online Block
        $users = User::with('group', 'privacy')
            ->withCount([
                'warnings' => function (Builder $query) {
                    $query->whereNotNull('torrent')->where('active', '1');
                },
            ])
            ->where('last_action', '>', now()->subMinutes(5))
            ->get();

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

        $past_uploaders = cache()->remember('month_uploaders', $expiresAt, function () use ($current) {
            return Torrent::with('user')
                ->where('created_at', '>', $current->copy()->subDays(30)->toDateTimeString())
                ->select(DB::raw('user_id, count(*) as value'))
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get();
        });

        $freeleech_tokens = FreeleechToken::where('user_id', $user->id)->get();
        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return view('home.index', [
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
            'freeleech_tokens'   => $freeleech_tokens,
            'bookmarks'          => $bookmarks,
        ]);
    }
}
