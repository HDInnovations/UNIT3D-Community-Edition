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
use App\Models\Poll;
use App\Models\Post;
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
                    ->sortBy(fn ($user) => $user->hidden || !$user->isVisible($user, 'other', 'show_online'))
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
            'topics'   => cache()->remember(
                'latest_topics:by-group:'.auth()->user()->group_id,
                $expiresAt,
                fn () => Topic::query()
                    ->with('user', 'user.group', 'latestPoster')
                    ->authorized(canReadTopic: true)
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
            'uploaders' => cache()->remember('top_uploaders', $expiresAt, fn () => Torrent::with(['user.group'])
                ->select(DB::raw('user_id, count(*) as value'))
                ->where('anon', '=', false)
                ->groupBy('user_id')
                ->latest('value')
                ->take(10)
                ->get()),
            'past_uploaders' => cache()->remember('month_uploaders', $expiresAt, fn () => Torrent::with(['user.group'])
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
