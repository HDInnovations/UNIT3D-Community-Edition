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
        $expiresAt = now()->addMinutes(1);

        // Authorized User
        $user = $request->user();

        // Latest Articles/News Block
        $articles = cache()->remember('latest_article', $expiresAt, fn () => Article::latest()->take(1)->get());

        foreach ($articles as $article) {
            $article->newNews = ($user->last_login->subDays(3)->getTimestamp() < $article->created_at->getTimestamp()) ? 1 : 0;
        }

        return view('home.index', [
            'user'               => $user,
            'personal_freeleech' => cache()->get('personal_freeleech:'.$user->id),
            'users'              => cache()->remember(
                'online_users:by-group:'.auth()->user()->group_id,
                $expiresAt,
                fn () => User::with('group', 'privacy')
                    ->withCount([
                        'warnings' => function (Builder $query): void {
                            $query->whereNotNull('torrent')->where('active', '1');
                        },
                    ])
                    ->where('last_action', '>', now()->subMinutes(5))
                    ->orderByRaw('(select position from `groups` where `groups`.id = users.group_id), group_id, username')
                    ->get()
                    ->sortBy(fn ($user) => $user->hidden || ! $user->isVisible($user, 'other', 'show_online'))
            ),
            'groups' => cache()->remember(
                'user-groups',
                $expiresAt,
                fn () => Group::select([
                    'id',
                    'name',
                    'color',
                    'effect',
                    'icon'
                ])
                    ->oldest('position')
                    ->get()
            ),
            'articles' => $articles,
            'newest'   => cache()->remember(
                'newest_torrents',
                $expiresAt,
                function () use ($user) {
                    $newest = Torrent::with(['user', 'category', 'type', 'resolution'])
                        ->withExists([
                            'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                            'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                            'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 1),
                            'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 0),
                            'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNull('completed_at'),
                            'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNotNull('completed_at'),
                        ])
                        ->withCount(['thanks', 'comments'])
                        ->latest()
                        ->take(5)
                        ->get();

                    $movieIds = $newest->where('movie_id', '!=', 0)->whereNotNull('movie_id')->pluck('movie_id');
                    $tvIds = $newest->where('tv_id', '!=', 0)->whereNotNull('tv_id')->pluck('tv_id');
                    $gameIds = $newest->where('igdb', '!=', 0)->whereNotNull('igdb')->pluck('igdb');

                    $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
                    $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
                    $games = [];

                    foreach ($gameIds as $gameId) {
                        $games[$gameId] = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($gameId);
                    }

                    $newest = $newest->map(function ($torrent) use ($movies, $tv, $games) {
                        $torrent->meta = match (true) {
                            (bool) $torrent->movie_id => $movies[$torrent->movie_id] ?? null,
                            (bool) $torrent->tv_id    => $tv[$torrent->tv_id] ?? null,
                            (bool) $torrent->igdb     => $games[$torrent->igdb] ?? null,
                            default                   => null,
                        };

                        return $torrent;
                    });

                    return $newest;
                }
            ),
            'seeded' => cache()->remember(
                'seeded_torrents',
                $expiresAt,
                function () use ($user) {
                    $seeded = Torrent::with(['user', 'category', 'type', 'resolution'])
                        ->withExists([
                            'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                            'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                            'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 1),
                            'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 0),
                            'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNull('completed_at'),
                            'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNotNull('completed_at'),
                        ])
                        ->withCount(['thanks', 'comments'])
                        ->latest('seeders')
                        ->take(5)
                        ->get();

                    $movieIds = $seeded->where('movie_id', '!=', 0)->whereNotNull('movie_id')->pluck('movie_id');
                    $tvIds = $seeded->where('tv_id', '!=', 0)->whereNotNull('tv_id')->pluck('tv_id');
                    $gameIds = $seeded->where('igdb', '!=', 0)->whereNotNull('igdb')->pluck('igdb');

                    $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
                    $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
                    $games = [];

                    foreach ($gameIds as $gameId) {
                        $games[$gameId] = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($gameId);
                    }

                    $seeded = $seeded->map(function ($torrent) use ($movies, $tv, $games) {
                        $torrent->meta = match (true) {
                            (bool) $torrent->movie_id => $movies[$torrent->movie_id] ?? null,
                            (bool) $torrent->tv_id    => $tv[$torrent->tv_id] ?? null,
                            (bool) $torrent->igdb     => $games[$torrent->igdb] ?? null,
                            default                   => null,
                        };

                        return $torrent;
                    });

                    return $seeded;
                }
            ),
            'dying' => cache()->remember(
                'dying_torrents',
                $expiresAt,
                function () use ($user) {
                    $dying = Torrent::with(['user', 'category', 'type', 'resolution'])
                        ->withExists([
                            'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                            'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                            'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 1),
                            'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 0),
                            'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNull('completed_at'),
                            'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNotNull('completed_at'),
                        ])
                        ->withCount(['thanks', 'comments'])
                        ->where('seeders', '=', 1)
                        ->where('times_completed', '>=', 1)
                        ->latest('leechers')
                        ->take(5)
                        ->get();
                    $movieIds = $dying->where('movie_id', '!=', 0)->whereNotNull('movie_id')->pluck('movie_id');
                    $tvIds = $dying->where('tv_id', '!=', 0)->whereNotNull('tv_id')->pluck('tv_id');
                    $gameIds = $dying->where('igdb', '!=', 0)->whereNotNull('igdb')->pluck('igdb');

                    $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
                    $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
                    $games = [];

                    foreach ($gameIds as $gameId) {
                        $games[$gameId] = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($gameId);
                    }

                    $dying = $dying->map(function ($torrent) use ($movies, $tv, $games) {
                        $torrent->meta = match (true) {
                            (bool) $torrent->movie_id => $movies[$torrent->movie_id] ?? null,
                            (bool) $torrent->tv_id    => $tv[$torrent->tv_id] ?? null,
                            (bool) $torrent->igdb     => $games[$torrent->igdb] ?? null,
                            default                   => null,
                        };

                        return $torrent;
                    });

                    return $dying;
                }
            ),
            'leeched' => cache()->remember(
                'leeched_torrents',
                $expiresAt,
                function () use ($user) {
                    $leeched = Torrent::with(['user', 'category', 'type', 'resolution'])
                        ->withExists([
                            'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                            'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                            'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 1),
                            'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 0),
                            'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNull('completed_at'),
                            'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNotNull('completed_at'),
                        ])
                        ->withCount(['thanks', 'comments'])
                        ->latest('leechers')
                        ->take(5)
                        ->get();

                    $movieIds = $leeched->where('movie_id', '!=', 0)->whereNotNull('movie_id')->pluck('movie_id');
                    $tvIds = $leeched->where('tv_id', '!=', 0)->whereNotNull('tv_id')->pluck('tv_id');
                    $gameIds = $leeched->where('igdb', '!=', 0)->whereNotNull('igdb')->pluck('igdb');

                    $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
                    $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
                    $games = [];

                    foreach ($gameIds as $gameId) {
                        $games[$gameId] = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($gameId);
                    }

                    $leeched = $leeched->map(function ($torrent) use ($movies, $tv, $games) {
                        $torrent->meta = match (true) {
                            (bool) $torrent->movie_id => $movies[$torrent->movie_id] ?? null,
                            (bool) $torrent->tv_id    => $tv[$torrent->tv_id] ?? null,
                            (bool) $torrent->igdb     => $games[$torrent->igdb] ?? null,
                            default                   => null,
                        };

                        return $torrent;
                    });

                    return $leeched;
                }
            ),
            'dead' => cache()->remember(
                'dead_torrents',
                $expiresAt,
                function () use ($user) {
                    $dead = Torrent::with(['user', 'category', 'type', 'resolution'])
                        ->withExists([
                            'bookmarks'          => fn ($query) => $query->where('user_id', '=', $user->id),
                            'freeleechTokens'    => fn ($query) => $query->where('user_id', '=', $user->id),
                            'history as seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 1),
                            'history as leeching' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 1)
                                ->where('seeder', '=', 0),
                            'history as not_completed' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNull('completed_at'),
                            'history as not_seeding' => fn ($query) => $query->where('user_id', '=', $user->id)
                                ->where('active', '=', 0)
                                ->where('seeder', '=', 1)
                                ->whereNotNull('completed_at'),
                        ])
                        ->withCount(['thanks', 'comments'])
                        ->where('seeders', '=', 0)
                        ->latest('leechers')
                        ->take(5)
                        ->get();

                    $movieIds = $dead->where('movie_id', '!=', 0)->whereNotNull('movie_id')->pluck('movie_id');
                    $tvIds = $dead->where('tv_id', '!=', 0)->whereNotNull('tv_id')->pluck('tv_id');
                    $gameIds = $dead->where('igdb', '!=', 0)->whereNotNull('igdb')->pluck('igdb');

                    $movies = Movie::with('genres')->whereIntegerInRaw('id', $movieIds)->get()->keyBy('id');
                    $tv = Tv::with('genres')->whereIntegerInRaw('id', $tvIds)->get()->keyBy('id');
                    $games = [];

                    foreach ($gameIds as $gameId) {
                        $games[$gameId] = \MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($gameId);
                    }

                    $dead = $dead->map(function ($torrent) use ($movies, $tv, $games) {
                        $torrent->meta = match (true) {
                            (bool) $torrent->movie_id => $movies[$torrent->movie_id] ?? null,
                            (bool) $torrent->tv_id    => $tv[$torrent->tv_id] ?? null,
                            (bool) $torrent->igdb     => $games[$torrent->igdb] ?? null,
                            default                   => null,
                        };

                        return $torrent;
                    });

                    return $dead;
                }
            ),
            'topics' => cache()->remember(
                'latest_topics:by-group:'.auth()->user()->group_id,
                $expiresAt,
                fn () => Topic::query()
                    ->with('user', 'user.group', 'latestPoster')
                    ->whereRelation('forumPermissions', [['show_forum', '=', 1], ['group_id', '=', auth()->user()->group_id]])
                    ->latest()
                    ->take(5)
                    ->get()
            ),
            'posts' => cache()->remember(
                'latest_posts:by-group:'.auth()->user()->group_id,
                $expiresAt,
                fn () => Post::query()
                    ->with('user', 'user.group', 'topic:id,name')
                    ->withCount('likes', 'dislikes', 'authorPosts', 'authorTopics')
                    ->withSum('tips', 'cost')
                    ->withExists([
                        'likes'    => fn ($query) => $query->where('user_id', '=', auth()->id()),
                        'dislikes' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    ])
                    ->whereNotIn(
                        'topic_id',
                        Topic::query()
                            ->whereRelation(
                                'forumPermissions',
                                fn ($query) => $query
                                    ->where('group_id', '=', auth()->user()->group_id)
                                    ->where(
                                        fn ($query) => $query
                                            ->where('show_forum', '!=', 1)
                                            ->orWhere('read_topic', '!=', 1)
                                    )
                            )
                            ->select('id')
                    )
                    ->latest()
                    ->take(5)
                    ->get()
            ),
            'featured' => cache()->remember(
                'latest_featured',
                $expiresAt,
                fn () => FeaturedTorrent::with([
                    'torrent',
                    'torrent.resolution',
                    'torrent.type',
                    'torrent.category',
                    'user',
                    'user.group'
                ])->get()
            ),
            'poll'      => cache()->remember('latest_poll', $expiresAt, fn () => Poll::latest()->first()),
            'uploaders' => cache()->remember('top_uploaders', $expiresAt, fn () => Torrent::with(['user', 'user.group'])
                ->select(DB::raw('user_id, count(*) as value'))
                ->where('anon', '=', false)
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get()),
            'past_uploaders' => cache()->remember('month_uploaders', $expiresAt, fn () => Torrent::with(['user', 'user.group'])
                ->where('created_at', '>', now()->subDays(30)->toDateTimeString())
                ->select(DB::raw('user_id, count(*) as value'))
                ->where('anon', '=', false)
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get()),
            'freeleech_tokens' => FreeleechToken::where('user_id', $user->id)->get(),
            'bookmarks'        => Bookmark::where('user_id', $user->id)->get(),
        ]);
    }
}
