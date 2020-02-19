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

use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use App\Repositories\ChatRepository;
use App\Repositories\TaggedUserRepository;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * @var TaggedUserRepository
     */
    private $tag;

    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * ForumController Constructor.
     *
     * @param TaggedUserRepository $tag
     * @param ChatRepository       $chat
     */
    public function __construct(TaggedUserRepository $tag, ChatRepository $chat)
    {
        $this->tag = $tag;
        $this->chat = $chat;
    }

    /**
     * Search For Topics.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $categories = Forum::all()->sortBy('position');

        $user = $request->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (!is_array($pests)) {
            $pests = [];
        }

        $topic_neos = $user->subscriptions->where('topic_id', '>', 0)->pluck('topic_id')->toArray();
        if (!is_array($topic_neos)) {
            $topic_neos = [];
        }

        $forum_neos = $user->subscriptions->where('forum_id', '>', 0)->pluck('forum_id')->toArray();
        if (!is_array($forum_neos)) {
            $forum_neos = [];
        }

        if ($request->has('body') && $request->input('body') != '') {
            $logger = 'forum.results_posts';
            $result = Post::selectRaw('posts.id as id,posts.*')->with(['topic', 'user'])->leftJoin('topics', 'posts.topic_id', '=', 'topics.id')->whereNotIn('topics.forum_id', $pests);
        }

        if (!isset($logger)) {
            $logger = 'forum.results_topics';
            $result = Topic::whereNotIn('topics.forum_id', $pests);
        }

        if ($request->has('body') && $request->input('body') != '') {
            $result->where([['posts.content', 'like', '%'.$request->input('body').'%']]);
        }
        if ($request->has('name')) {
            $result->where([['topics.name', 'like', '%'.$request->input('name').'%']]);
        }
        if ($request->has('subscribed') && $request->input('subscribed') == 1) {
            $result->where(function ($query) use ($topic_neos, $forum_neos) {
                $query->whereIn('topics.id', $topic_neos)->orWhereIn('topics.forum_id', $forum_neos);
            });
        } elseif ($request->has('notsubscribed') && $request->input('notsubscribed') == 1) {
            $result->whereNotIn('topics.id', $topic_neos)->whereNotIn('topics.forum_id', $forum_neos);
        }

        if ($request->has('implemented') && $request->input('implemented') == 1) {
            $result->where('topics.implemented', '=', 1);
        }
        if ($request->has('approved') && $request->input('approved') == 1) {
            $result->where('topics.approved', '=', 1);
        }
        if ($request->has('denied') && $request->input('denied') == 1) {
            $result->where('topics.denied', '=', 1);
        }
        if ($request->has('solved') && $request->input('solved') == 1) {
            $result->where('topics.solved', '=', 1);
        }
        if ($request->has('invalid') && $request->input('invalid') == 1) {
            $result->where('topics.invalid', '=', 1);
        }
        if ($request->has('bug') && $request->input('bug') == 1) {
            $result->where('topics.bug', '=', 1);
        }
        if ($request->has('suggestion') && $request->input('suggestion') == 1) {
            $result->where('topics.suggestion', '=', 1);
        }

        if ($request->has('closed') && $request->input('closed') == 1) {
            $result->where('topics.state', '=', 'close');
        }
        if ($request->has('open') && $request->input('open') == 1) {
            $result->where('topics.state', '=', 'open');
        }

        if ($request->has('category')) {
            $category = (int) $request->input('category');
            if ($category > 0 && $category < 99999999999) {
                $children = Forum::where('parent_id', '=', $category)->get()->toArray();
                if (is_array($children)) {
                    $result->where(function ($query) use ($category, $children) {
                        $query->where('topics.forum_id', '=', $category)->orWhereIn('topics.forum_id', $children);
                    });
                }
            }
        }

        if ($request->has('body') && $request->input('body') != '') {
            if ($request->has('sorting') && $request->input('sorting') != null) {
                $sorting = sprintf('posts.%s', $request->input('sorting'));
                $direction = $request->input('direction');
            } else {
                $sorting = 'posts.id';
                $direction = 'desc';
            }
            $results = $result->orderBy($sorting, $direction)->paginate(25);
        } else {
            if ($request->has('sorting') && $request->input('sorting') != null) {
                $sorting = sprintf('topics.%s', $request->input('sorting'));
                $direction = $request->input('direction');
            } else {
                $sorting = 'topics.last_reply_at';
                $direction = 'desc';
            }
            $results = $result->orderBy($sorting, $direction)->paginate(25);
        }

        $results->setPath('?name='.$request->input('name'));

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        $params = $request->all();

        return view($logger, [
            'categories' => $categories,
            'results'    => $results,
            'user'       => $user,
            'name'       => $request->input('name'),
            'body'       => $request->input('body'),
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
            'params'     => $params,
        ]);
    }

    /**
     * Search For Subscribed Forums & Topics.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscriptions(Request $request)
    {
        $user = $request->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (!is_array($pests)) {
            $pests = [];
        }

        $topic_neos = $user->subscriptions->where('topic_id', '>', '0')->pluck('topic_id')->toArray();
        if (!is_array($topic_neos)) {
            $topic_neos = [];
        }

        $forum_neos = $user->subscriptions->where('forum_id', '>', '0')->pluck('forum_id')->toArray();
        if (!is_array($forum_neos)) {
            $forum_neos = [];
        }

        $logger = 'forum.subscriptions';
        $result = Forum::with('subscription_topics')->selectRaw('forums.id,max(forums.position) as position,max(forums.num_topic) as num_topic,max(forums.num_post) as num_post,max(forums.last_topic_id) as last_topic_id,max(forums.last_topic_name) as last_topic_name,max(forums.last_topic_slug) as last_topic_slug,max(forums.last_post_user_id) as last_post_user_id,max(forums.last_post_user_username) as last_post_user_username,max(forums.name) as name,max(forums.slug) as slug,max(forums.description) as description,max(forums.parent_id) as parent_id,max(forums.created_at),max(forums.updated_at),max(topics.id) as topic_id,max(topics.created_at) as topic_created_at')->leftJoin('topics', 'forums.id', '=', 'topics.forum_id')->whereNotIn('topics.forum_id', $pests)->where(function ($query) use ($topic_neos, $forum_neos) {
            $query->whereIn('topics.id', $topic_neos)->orWhereIn('forums.id', $forum_neos);
        })->groupBy('forums.id');

        $results = $result->orderBy('topic_created_at', 'desc')->paginate(25);
        $results->setPath('?name='.$request->input('name'));

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        $params = $request->all();

        return view($logger, [
            'results'    => $results,
            'user'       => $user,
            'name'       => $request->input('name'),
            'body'       => $request->input('body'),
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
            'params'     => $params,
            'forum_neos' => $forum_neos,
            'topic_neos' => $topic_neos,
        ]);
    }

    /**
     * Latest Topics.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latestTopics(Request $request)
    {
        $user = $request->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (!is_array($pests)) {
            $pests = [];
        }

        $results = Topic::whereNotIn('topics.forum_id', $pests)->latest()->paginate(25);

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        return view('forum.latest_topics', [
            'results'    => $results,
            'user'       => $user,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }

    /**
     * Latest Posts.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function latestPosts(Request $request)
    {
        $user = $request->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (!is_array($pests)) {
            $pests = [];
        }

        $results = Post::selectRaw('posts.id as id,posts.*')->with(['topic', 'user'])->leftJoin('topics', 'posts.topic_id', '=', 'topics.id')->whereNotIn('topics.forum_id', $pests)->orderBy('posts.created_at', 'desc')->paginate(25);

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        return view('forum.latest_posts', [
            'results'    => $results,
            'user'       => $user,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }

    /**
     * Show All Forums.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Forum::all()->sortBy('position');

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        return view('forum.index', [
            'categories' => $categories,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }

    /**
     * Show Forums And Topics Inside.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        // Find the topic
        $forum = Forum::findOrFail($id);

        // Total Forums Count
        $num_forums = Forum::count();
        // Total Posts Count
        $num_posts = Post::count();
        // Total Topics Count
        $num_topics = Topic::count();

        // Check if this is a category or forum
        if ($forum->parent_id == 0) {
            return redirect()->route('forums.categories.show', ['id' => $forum->id]);
        }

        // Check if the user has permission to view the forum
        $category = Forum::findOrFail($forum->parent_id);
        if ($category->getPermission()->show_forum != true) {
            return redirect()->route('forums.index')
                ->withErrors('You Do Not Have Access To This Forum!');
        }

        // Fetch topics->posts in descending order
        $topics = $forum->topics()->latest('pinned')->latest('last_reply_at')->latest()->paginate(25);

        return view('forum.display', [
            'forum'      => $forum,
            'topics'     => $topics,
            'category'   => $category,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }
}
