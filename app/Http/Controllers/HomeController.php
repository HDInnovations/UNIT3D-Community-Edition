<?php

declare(strict_types=1);

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
use App\Models\Comment;
use App\Models\FeaturedTorrent;
use App\Models\FreeleechToken;
use App\Models\Group;
use App\Models\History;
use App\Models\Peer;
use App\Models\Poll;
use App\Models\Post;
use App\Models\Thank;
use App\Models\Topic;
use App\Models\Torrent;
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
        $expiresAt = now()->addMinutes(5);

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
                    ->where('last_action', '>', now()->subMinutes(60))
                    ->orderByRaw('(select position from `groups` where `groups`.id = users.group_id), group_id, username')
                    ->get()
                    ->sortBy(fn ($user) => $user->privacy?->hidden || !$user->isVisible($user, 'other', 'show_online'))
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
            'topics'   => Topic::query()
                ->with(['user', 'user.group', 'latestPoster', 'reads' => fn ($query) => $query->whereBelongsto($user)])
                ->authorized(canReadTopic: true)
                ->latest()
                ->take(5)
                ->get(),
            'posts' => cache()->remember(
                'latest_posts:by-group:'.auth()->user()->group_id,
                $expiresAt,
                fn () => Post::query()
                    ->with('user', 'user.group', 'topic:id,name')
                    ->withCount('likes', 'dislikes', 'authorPosts', 'authorTopics')
                    ->withSum('tips', 'bon')
                    ->withExists([
                        'likes'    => fn ($query) => $query->where('user_id', '=', auth()->id()),
                        'dislikes' => fn ($query) => $query->where('user_id', '=', auth()->id()),
                    ])
                    ->authorized(canReadTopic: true)
                    ->latest()
                    ->take(5)
                    ->get()
            ),
            'featured' => cache()->remember(
                'latest_featured',
                $expiresAt,
                fn () => FeaturedTorrent::with([
                    'torrent' => ['resolution', 'type', 'category'],
                    'user.group'
                ])->get()
            ),
            'poll'      => cache()->remember('latest_poll', $expiresAt, fn () => Poll::latest()->first()),
            'uploaders' => cache()->remember(
                'top-users:uploaders',
                3_600,
                fn () => Torrent::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, COUNT(user_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->where('anon', '=', false)
                    ->groupBy('user_id')
                    ->orderByDesc('value')
                    ->take(8)
                    ->get()
            ),
            'downloaders' => cache()->remember(
                'top-users:downloaders',
                3_600,
                fn () => History::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, count(distinct torrent_id) as value'))
                    ->whereNotNull('completed_at')
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->groupBy('user_id')
                    ->orderByDesc('value')
                    ->take(8)
                    ->get()
            ),
            'uploaded' => cache()->remember(
                'top-users:uploaded',
                3_600,
                fn () => User::select(['id', 'group_id', 'username', 'uploaded', 'image'])
                    ->where('id', '!=', User::SYSTEM_USER_ID)
                    ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                    ->orderByDesc('uploaded')
                    ->take(8)
                    ->get(),
            ),
            'downloaded' => cache()->remember(
                'top-users:downloaded',
                3_600,
                fn () => User::select(['id', 'group_id', 'username', 'downloaded', 'image'])
                    ->where('id', '!=', User::SYSTEM_USER_ID)
                    ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                    ->orderByDesc('downloaded')
                    ->take(8)
                    ->get(),
            ),
            'seeders' => cache()->remember(
                'top-users:seeders',
                3_600,
                fn () => Peer::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, count(distinct torrent_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->where('seeder', '=', 1)
                    ->where('active', '=', 1)
                    ->groupBy('user_id')
                    ->orderByDesc('value')
                    ->take(8)
                    ->get(),
            ),
            'seedtimes' => cache()->remember(
                'top-users:seedtimes',
                3_600,
                fn () => User::withSum('history as seedtime', 'seedtime')
                    ->where('id', '!=', User::SYSTEM_USER_ID)
                    ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                    ->orderByDesc('seedtime')
                    ->take(8)
                    ->get(),
            ),
            'served' => cache()->remember(
                'top-users:served',
                3_600,
                fn () => User::withCount('uploadSnatches')
                    ->where('id', '!=', User::SYSTEM_USER_ID)
                    ->whereNotIn('group_id', Group::select('id')->whereIn('slug', ['banned', 'validating', 'disabled', 'pruned']))
                    ->orderByDesc('upload_snatches_count')
                    ->take(8)
                    ->get(),
            ),
            'commenters' => cache()->remember(
                'top-users:commenters',
                3_600,
                fn () => Comment::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, COUNT(user_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->where('anon', '=', false)
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(8)
                    ->get()
            ),
            'posters' => cache()->remember(
                'top-users:posters',
                3_600,
                fn () => Post::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, COUNT(user_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(8)
                    ->get()
            ),
            'thankers' => cache()->remember(
                'top-users:thankers',
                3_600,
                fn () => Thank::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, COUNT(user_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(8)
                    ->get()
            ),
            'personals' => cache()->remember(
                'top-users:personals',
                3_600,
                fn () => Torrent::with(['user' , 'user.group'])
                    ->select(DB::raw('user_id, COUNT(user_id) as value'))
                    ->where('user_id', '!=', User::SYSTEM_USER_ID)
                    ->where('anon', '=', false)
                    ->where('personal_release', '=', 1)
                    ->groupBy('user_id')
                    ->orderByDesc('value')
                    ->take(8)
                    ->get()
            ),
            'freeleech_tokens' => FreeleechToken::where('user_id', $user->id)->get(),
            'bookmarks'        => Bookmark::where('user_id', $user->id)->get(),
        ]);
    }
}
