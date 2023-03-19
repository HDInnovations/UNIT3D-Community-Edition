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
use App\Models\Movie;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Topic;
use App\Models\Torrent;
use App\Models\Tv;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\Staff\HomeControllerTest
 */
class HomeController extends Controller
{
    /**
     * Display Home Page.
     *
     * @throws Exception
     */
    public function index(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        // For Cache
        $current = Carbon::now();
        $expiresAt = $current->addMinutes(1);

        // Authorized User
        $user = $request->user();

        // Latest Articles/News Block
        $articles = cache()->remember('latest_article', $expiresAt, fn () => Article::latest()->take(1)->get());
        foreach ($articles as $article) {
            $article->newNews = ($user->last_login->subDays(3)->getTimestamp() < $article->created_at->getTimestamp()) ? 1 : 0;
        }

        // Latest Torrents Block
        $personalFreeleech = cache()->get('personal_freeleech:'.$user->id);

        $newest = cache()->remember('newest_torrents', $expiresAt, function () {
            $newest = Torrent::with(['user', 'category', 'type', 'resolution'])
                ->withExists([
                    'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->withCount(['thanks', 'comments'])
                ->latest()
                ->take(5)
                ->get();

            $movieIds = $newest->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $newest->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $newest->where('meta', '=', 'game')->pluck('igdb');

            $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
            $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
            if ($gameIds->isNotEmpty()) {
                $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
            }

            $newest = $newest->map(function ($torrent) use ($movies, $tv) {
                $torrent->meta = match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                };

                return $torrent;
            });

            return $newest;
        });

        $seeded = cache()->remember('seeded_torrents', $expiresAt, function () {
            $seeded = Torrent::with(['user', 'category', 'type', 'resolution'])
                ->withExists([
                    'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->withCount(['thanks', 'comments'])
                ->latest('seeders')
                ->take(5)
                ->get();

            $movieIds = $seeded->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $seeded->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $seeded->where('meta', '=', 'game')->pluck('igdb');

            $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
            $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
            if ($gameIds->isNotEmpty()) {
                $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
            }

            $seeded = $seeded->map(function ($torrent) use ($movies, $tv) {
                $torrent->meta = match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                };

                return $torrent;
            });

            return $seeded;
        });

        $leeched = cache()->remember('leeched_torrents', $expiresAt, function () {
            $leeched = Torrent::with(['user', 'category', 'type', 'resolution'])
                ->withExists([
                    'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->withCount(['thanks', 'comments'])
                ->latest('leechers')
                ->take(5)
                ->get();

            $movieIds = $leeched->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $leeched->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $leeched->where('meta', '=', 'game')->pluck('igdb');

            $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
            $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
            if ($gameIds->isNotEmpty()) {
                $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
            }

            $leeched = $leeched->map(function ($torrent) use ($movies, $tv) {
                $torrent->meta = match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                };

                return $torrent;
            });

            return $leeched;
        });

        $dying = cache()->remember('dying_torrents', $expiresAt, function () {
            $dying = Torrent::with(['user', 'category', 'type', 'resolution'])
                ->withExists([
                    'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->withCount(['thanks', 'comments'])
                ->where('seeders', '=', 1)
                ->where('times_completed', '>=', 1)
                ->latest('leechers')
                ->take(5)
                ->get();

            $movieIds = $dying->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $dying->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $dying->where('meta', '=', 'game')->pluck('igdb');

            $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
            $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
            if ($gameIds->isNotEmpty()) {
                $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
            }

            $dying = $dying->map(function ($torrent) use ($movies, $tv) {
                $torrent->meta = match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                };

                return $torrent;
            });

            return $dying;
        });

        $dead = cache()->remember('dead_torrents', $expiresAt, function () {
            $dead = Torrent::with(['user', 'category', 'type', 'resolution'])
                ->withExists([
                    'bookmarks'       => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    'freeleechTokens' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                ])
                ->selectRaw("
                    CASE
                        WHEN category_id IN (SELECT `id` from `categories` where `movie_meta` = 1) THEN 'movie'
                        WHEN category_id IN (SELECT `id` from `categories` where `tv_meta` = 1) THEN 'tv'
                        WHEN category_id IN (SELECT `id` from `categories` where `game_meta` = 1) THEN 'game'
                        WHEN category_id IN (SELECT `id` from `categories` where `music_meta` = 1) THEN 'music'
                        WHEN category_id IN (SELECT `id` from `categories` where `no_meta` = 1) THEN 'no'
                    END as meta
                ")
                ->withCount(['thanks', 'comments'])
                ->where('seeders', '=', 0)
                ->latest('leechers')
                ->take(5)
                ->get();

            $movieIds = $dead->where('meta', '=', 'movie')->pluck('tmdb');
            $tvIds = $dead->where('meta', '=', 'tv')->pluck('tmdb');
            $gameIds = $dead->where('meta', '=', 'game')->pluck('igdb');

            $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
            $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
            if ($gameIds->isNotEmpty()) {
                $games = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->whereIntegerInRaw('id', $gameIds);
            }

            $dead = $dead->map(function ($torrent) use ($movies, $tv) {
                $torrent->meta = match ($torrent->meta) {
                    'movie' => $movies[$torrent->tmdb] ?? null,
                    'tv'    => $tv[$torrent->tmdb] ?? null,
                    'game'  => $games[$torrent->igdb] ?? null,
                    default => null,
                };

                return $torrent;
            });

            return $dead;
        });

        // Latest Topics Block
        $topics = cache()->remember('latest_topics', $expiresAt, fn () => Topic::with('forum')->latest()->take(5)->get());

        // Latest Posts Block
        $posts = cache()->remember('latest_posts', $expiresAt, fn () => Post::with('topic', 'user')->withCount('authorPosts', 'authorTopics')->latest()->take(5)->get());

        // Online Block
        $users = cache()->remember('online_users', $expiresAt, fn () => User::with('group', 'privacy')
            ->withCount([
                'warnings' => function (Builder $query): void {
                    $query->whereNotNull('torrent')->where('active', '1');
                },
            ])
            ->where('last_action', '>', now()->subMinutes(5))
            ->get());

        $groups = cache()->remember('user-groups', $expiresAt, fn () => Group::select(['name', 'color', 'effect', 'icon'])->oldest('position')->get());

        // Featured Torrents Block
        $featured = cache()->remember('latest_featured', $expiresAt, fn () => FeaturedTorrent::with('torrent', 'torrent.resolution', 'torrent.type', 'torrent.category', 'user', 'user.group')->get());

        // Latest Poll Block
        $poll = cache()->remember('latest_poll', $expiresAt, fn () => Poll::latest()->first());

        // Top Uploaders Block
        $uploaders = cache()->remember('top_uploaders', $expiresAt, fn () => Torrent::with(['user', 'user.group'])
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get());

        $pastUploaders = cache()->remember('month_uploaders', $expiresAt, fn () => Torrent::with(['user', 'user.group'])
            ->where('created_at', '>', $current->copy()->subDays(30)->toDateTimeString())
            ->select(DB::raw('user_id, count(*) as value'))
            ->groupBy('user_id')
            ->latest('value')
            ->take(10)
            ->get());

        $freeleechTokens = FreeleechToken::where('user_id', $user->id)->get();
        $bookmarks = Bookmark::where('user_id', $user->id)->get();

        return view('home.index', [
            'user'               => $user,
            'personal_freeleech' => $personalFreeleech,
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
            'past_uploaders'     => $pastUploaders,
            'freeleech_tokens'   => $freeleechTokens,
            'bookmarks'          => $bookmarks,
        ]);
    }
}
