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

use App\Post;
use App\User;
use App\Forum;
use App\Topic;
use Brian2694\Toastr\Toastr;
use Illuminate\Http\Request;
use App\Repositories\ChatRepository;
use App\Achievements\UserMade25Posts;
use App\Achievements\UserMade50Posts;
use App\Achievements\UserMade100Posts;
use App\Achievements\UserMade200Posts;
use App\Achievements\UserMade300Posts;
use App\Achievements\UserMade400Posts;
use App\Achievements\UserMade500Posts;
use App\Achievements\UserMade600Posts;
use App\Achievements\UserMade700Posts;
use App\Achievements\UserMade800Posts;
use App\Achievements\UserMade900Posts;
use App\Achievements\UserMadeFirstPost;
use App\Repositories\TaggedUserRepository;

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
     * @var Toastr
     */
    private $toastr;

    /**
     * ForumController Constructor.
     *
     * @param TaggedUserRepository $tag
     * @param ChatRepository       $chat
     * @param Toastr               $toastr
     */
    public function __construct(TaggedUserRepository $tag, ChatRepository $chat, Toastr $toastr)
    {
        $this->tag = $tag;
        $this->chat = $chat;
        $this->toastr = $toastr;
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
        $categories = Forum::oldest('position')->get();

        $user = auth()->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (! is_array($pests)) {
            $pests = [];
        }

        $topic_neos = $user->subscriptions->where('topic_id', '>', 0)->pluck('topic_id')->toArray();
        if (! is_array($topic_neos)) {
            $topic_neos = [];
        }

        $forum_neos = $user->subscriptions->where('forum_id', '>', 0)->pluck('forum_id')->toArray();
        if (! is_array($forum_neos)) {
            $forum_neos = [];
        }

        if ($request->has('body') && $request->input('body') != '') {
            $logger = 'forum.results_posts';
            $result = Post::selectRaw('posts.id as id,posts.*')->with(['topic', 'user'])->leftJoin('topics', 'posts.topic_id', '=', 'topics.id')->whereNotIn('topics.forum_id', $pests);
        }

        if (! isset($logger)) {
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
            $result->where(function ($query) use ($topic_neos,$forum_neos) {
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
                    $result->where(function ($query) use ($category,$children) {
                        $query->where('topics.forum_id', '=', $category)->orWhereIn('topics.forum_id', $children);
                    });
                }
            }
        }
        $direction = 2;
        $order = 'desc';
        if ($request->has('direction') && $request->input('direction') == 1) {
            $direction = 1;
            $order = 'asc';
        }
        if ($request->has('body') && $request->input('body') != '') {
            $sorting = 'posts.id';
            if ($request->has('sorting') && $request->input('sorting') == 'created_at') {
                $sorting = 'posts.created_at';
            }
            $results = $result->orderBy($sorting, $direction)->paginate(25);
        } else {
            $sorting = 'topics.last_reply_at';
            if ($request->has('sorting') && $request->input('sorting') == 'created_at') {
                $sorting = 'topics.created_at';
            }
            $results = $result->orderBy($sorting, $order)->paginate(25);
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
                'results' => $results,
                'user' => $user,
                'name' => $request->input('name'),
                'body' => $request->input('body'),
                'num_posts'  => $num_posts,
                'num_forums' => $num_forums,
                'num_topics' => $num_topics,
                'params'     => $params,
            ]
        );
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
        $user = auth()->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (! is_array($pests)) {
            $pests = [];
        }

        $topic_neos = $user->subscriptions->where('topic_id', '>', '0')->pluck('topic_id')->toArray();
        if (! is_array($topic_neos)) {
            $topic_neos = [];
        }

        $forum_neos = $user->subscriptions->where('forum_id', '>', '0')->pluck('forum_id')->toArray();
        if (! is_array($forum_neos)) {
            $forum_neos = [];
        }

        $logger = 'forum.subscriptions';
        $result = Forum::with('subscription_topics')->selectRaw('forums.id,max(forums.position) as position,max(forums.num_topic) as num_topic,max(forums.num_post) as num_post,max(forums.last_topic_id) as last_topic_id,max(forums.last_topic_name) as last_topic_name,max(forums.last_topic_slug) as last_topic_slug,max(forums.last_post_user_id) as last_post_user_id,max(forums.last_post_user_username) as last_post_user_username,max(forums.name) as name,max(forums.slug) as slug,max(forums.description) as description,max(forums.parent_id) as parent_id,max(forums.created_at),max(forums.updated_at),max(topics.id) as topic_id,max(topics.created_at) as topic_created_at')->leftJoin('topics', 'forums.id', '=', 'topics.forum_id')->whereNotIn('topics.forum_id', $pests)->where(function ($query) use ($topic_neos,$forum_neos) {
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
                'results' => $results,
                'user' => $user,
                'name' => $request->input('name'),
                'body' => $request->input('body'),
                'num_posts'  => $num_posts,
                'num_forums' => $num_forums,
                'num_topics' => $num_topics,
                'params'     => $params,
                'forum_neos' => $forum_neos,
                'topic_neos' => $topic_neos,
            ]
        );
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
        $user = auth()->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (! is_array($pests)) {
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
                'results' => $results,
                'user' => $user,
                'num_posts'  => $num_posts,
                'num_forums' => $num_forums,
                'num_topics' => $num_topics,
            ]
        );
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
        $user = auth()->user();

        $pests = $user->group->permissions->where('show_forum', '=', 0)->pluck('forum_id')->toArray();
        if (! is_array($pests)) {
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
                'results' => $results,
                'user' => $user,
                'num_posts'  => $num_posts,
                'num_forums' => $num_forums,
                'num_topics' => $num_topics,
            ]
        );
    }

    /**
     * Show All Forums.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Forum::oldest('position')->get();

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
     * Show The Forum Category.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category($slug, $id)
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
        if ($forum->parent_id != 0) {
            return redirect()->route('forum_display', ['slug' => $forum->slug, 'id' => $forum->id]);
        }

        // Check if the user has permission to view the forum
        $category = Forum::findOrFail($forum->id);
        if ($category->getPermission()->show_forum != true) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Do Not Have Access To This Category!', 'Whoops!', ['options']));
        }

        // Fetch topics->posts in descending order
        $topics = $forum->sub_topics()->latest('pinned')->latest('last_reply_at')->latest()->paginate(25);

        return view('forum.category', [
            'forum'    => $forum,
            'topics'   => $topics,
            'category' => $category,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }

    /**
     * Show Forums And Topics Inside.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display($slug, $id)
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
            return redirect()->route('forum_category', ['slug' => $forum->slug, 'id' => $forum->id]);
        }

        // Check if the user has permission to view the forum
        $category = Forum::findOrFail($forum->parent_id);
        if ($category->getPermission()->show_forum != true) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Do Not Have Access To This Forum!', 'Whoops!', ['options']));
        }

        // Fetch topics->posts in descending order
        $topics = $forum->topics()->latest('pinned')->latest('last_reply_at')->latest()->paginate(25);

        return view('forum.display', [
            'forum'    => $forum,
            'topics'   => $topics,
            'category' => $category,
            'num_posts'  => $num_posts,
            'num_forums' => $num_forums,
            'num_topics' => $num_topics,
        ]);
    }

    /**
     * Show The Topic.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topic($slug, $id)
    {
        // Find the topic
        $topic = Topic::findOrFail($id);

        // Get the forum of the topic
        $forum = $topic->forum;

        // Get The category of the forum
        $category = $forum->getCategory();

        // Get all posts
        $posts = $topic->posts()->with(['user', 'tips'])->paginate(25);

        // First post
        $firstPost = Post::with('tips')->where('topic_id', '=', $topic->id)->first();

        // The user can post a topic here ?
        if ($category->getPermission()->read_topic != true) {
            // Redirect him to the forum index
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Do Not Have Access To Read This Topic!', 'Whoops!', ['options']));
        }

        // Increment view
        $topic->views++;
        $topic->save();

        return view('forum.topic', [
            'topic'     => $topic,
            'forum'     => $forum,
            'category'  => $category,
            'posts'     => $posts,
            'firstPost' => $firstPost,
        ]);
    }

    /**
     * Add A Post To A Topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = $forum->getCategory();

        // The user has the right to create a topic here?
        if (! $category->getPermission()->reply_topic || ($topic->state == 'close' && ! auth()->user()->group->is_modo)) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Cannot Reply To This Topic!', 'Whoops!', ['options']));
        }

        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = $user->id;
        $post->topic_id = $topic->id;

        $v = validator($post->toArray(), [
            'content'  => 'required|min:1',
            'user_id'  => 'required',
            'topic_id' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $post->save();

            $appurl = config('app.url');
            $href = "{$appurl}/forums/topic/{$topic->slug}.{$topic->id}?page={$post->getPageNumber()}#post-{$post->id}";
            $message = "{$user->username} has tagged you in a forum post. You can view it [url=$href] HERE [/url]";

            if ($this->tag->hasTags($request->input('content'))) {
                //$this->tag->setDebug(true);

                if ($this->tag->contains($request->input('content'), '@here') && $user->group->is_modo) {
                    $users = collect([]);

                    $topic->posts()->get()->each(function ($p, $v) use ($users) {
                        $users->push($p->user);
                    });

                    $this->tag->messagePostUsers(
                        'forum',
                        $users,
                        $user,
                        'Staff',
                        $post
                    );
                } else {
                    $this->tag->messageTaggedPostUsers(
                        'forum',
                        $request->input('content'),
                        $user,
                        $user->username,
                        $post
                    );
                }
            }

            // Save last post user data to topic table
            $topic->last_post_user_id = $user->id;
            $topic->last_post_user_username = $user->username;

            // Count post in topic
            $topic->num_post = Post::where('topic_id', '=', $topic->id)->count();

            // Update time
            $topic->last_reply_at = $post->created_at;

            // Save
            $topic->save();

            // Count posts
            $forum->num_post = $forum->getPostCount($forum->id);
            // Count topics
            $forum->num_topic = $forum->getTopicCount($forum->id);
            // Save last post user data to the forum table
            $forum->last_post_user_id = $user->id;
            $forum->last_post_user_username = $user->username;
            // Save last topic data to the forum table
            $forum->last_topic_id = $topic->id;
            $forum->last_topic_name = $topic->name;
            // Save
            $forum->save();

            // Post To Chatbox
            $appurl = config('app.url');
            $postUrl = "{$appurl}/forums/topic/{$topic->slug}.{$topic->id}?page={$post->getPageNumber()}#post-{$post->id}";
            $realUrl = "/forums/topic/{$topic->slug}.{$topic->id}?page={$post->getPageNumber()}#post-{$post->id}";
            $profileUrl = "{$appurl}/{$user->username}.{$user->id}";
            $this->chat->systemMessage(":robot: [b][color=#fb9776]System[/color][/b] : [url=$profileUrl]{$user->username}[/url] has left a reply on topic [url={$postUrl}]{$topic->name}[/url]");

            // Notify All Subscribers Of New Reply
            if ($topic->first_user_poster_id != $user->id) {
                $topic->notifyStarter($user, $topic, $post);
            }
            $topic->notifySubscribers($user, $topic, $post);

            //Achievements
            $user->unlock(new UserMadeFirstPost(), 1);
            $user->addProgress(new UserMade25Posts(), 1);
            $user->addProgress(new UserMade50Posts(), 1);
            $user->addProgress(new UserMade100Posts(), 1);
            $user->addProgress(new UserMade200Posts(), 1);
            $user->addProgress(new UserMade300Posts(), 1);
            $user->addProgress(new UserMade400Posts(), 1);
            $user->addProgress(new UserMade500Posts(), 1);
            $user->addProgress(new UserMade600Posts(), 1);
            $user->addProgress(new UserMade700Posts(), 1);
            $user->addProgress(new UserMade800Posts(), 1);
            $user->addProgress(new UserMade900Posts(), 1);

            return redirect($realUrl)
                ->with($this->toastr->success('Post Successfully Posted', 'Yay!', ['options']));
        }
    }

    /**
     * Topic Add Form.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm(Request $request, $slug, $id)
    {
        $forum = Forum::findOrFail($id);
        $category = $forum->getCategory();

        // The user has the right to create a topic here?
        if ($category->getPermission()->start_topic != true) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Cannot Start A New Topic Here!', 'Whoops!', ['options']));
        }

        return view('forum.new_topic', [
            'forum'    => $forum,
            'category' => $category,
            'title'    => $request->input('title'),
        ]);
    }

    /**
     * Create A New Topic In The Forum.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function newTopic(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $forum = Forum::findOrFail($id);
        $category = $forum->getCategory();

        // The user has the right to create a topic here?
        if ($category->getPermission()->start_topic != true) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error('You Cannot Start A New Topic Here!', 'Whoops!', ['options']));
        }

        // Create The Topic
        $topic = new Topic();
        $topic->name = $request->input('title');
        $topic->slug = str_slug($request->input('title'));
        $topic->state = 'open';
        $topic->first_post_user_id = $topic->last_post_user_id = $user->id;
        $topic->first_post_user_username = $topic->last_post_user_username = $user->username;
        $topic->views = 0;
        $topic->pinned = false;
        $topic->forum_id = $forum->id;

        $v = validator($topic->toArray(), [
            'name'                     => 'required',
            'slug'                     => 'required',
            'state'                    => 'required',
            'num_post'                 => '',
            'first_post_user_id'       => 'required',
            'first_post_user_username' => 'required',
            'last_post_user_id'        => '',
            'last_post_user_username'  => '',
            'views'                    => '',
            'pinned'                   => '',
            'forum_id'                 => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('forum_index')
                ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
        } else {
            $topic->save();

            $post = new Post();
            $post->content = $request->input('content');
            $post->user_id = $user->id;
            $post->topic_id = $topic->id;

            $v = validator($post->toArray(), [
                'content'  => 'required',
                'user_id'  => 'required',
                'topic_id' => 'required',
            ]);

            if ($v->fails()) {
                return redirect()->route('forum_index')
                    ->with($this->toastr->error($v->errors()->toJson(), 'Whoops!', ['options']));
            } else {
                $post->save();
                $topic->num_post = 1;
                $topic->last_reply_at = $post->created_at;
                $topic->save();
                $forum->num_topic = $forum->getTopicCount($forum->id);
                $forum->num_post = $forum->getPostCount($forum->id);
                $forum->last_topic_id = $topic->id;
                $forum->last_topic_name = $topic->name;
                $forum->last_topic_slug = $topic->slug;
                $forum->last_post_user_id = $user->id;
                $forum->last_post_user_username = $user->username;
                $forum->save();

                $forum->notifySubscribers($user, $topic);

                // Post To ShoutBox
                $appurl = config('app.url');
                $topicUrl = "{$appurl}/forums/topic/{$topic->slug}.{$topic->id}";
                $profileUrl = "{$appurl}/{$user->username}.{$user->id}";

                $this->chat->systemMessage(":robot: [b][color=#fb9776]System[/color][/b] : [url={$profileUrl}]{$user->username}[/url] has created a new topic [url={$topicUrl}]{$topic->name}[/url]");

                //Achievements
                $user->unlock(new UserMadeFirstPost(), 1);
                $user->addProgress(new UserMade25Posts(), 1);
                $user->addProgress(new UserMade50Posts(), 1);
                $user->addProgress(new UserMade100Posts(), 1);
                $user->addProgress(new UserMade200Posts(), 1);
                $user->addProgress(new UserMade300Posts(), 1);
                $user->addProgress(new UserMade400Posts(), 1);
                $user->addProgress(new UserMade500Posts(), 1);
                $user->addProgress(new UserMade600Posts(), 1);
                $user->addProgress(new UserMade700Posts(), 1);
                $user->addProgress(new UserMade800Posts(), 1);
                $user->addProgress(new UserMade900Posts(), 1);

                return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
                    ->with($this->toastr->success('Topic Created Successfully!', 'Yay!', ['options']));
            }
        }
    }

    /**
     * Topic Edit Form.
     *
     * @param $slug
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $categories = Forum::where('parent_id', '!=', 0)->get();

        return view('forum.edit_topic', ['topic' => $topic, 'categories' => $categories]);
    }

    /**
     * Edit Topic In The Forum.
     *
     * @param \Illuminate\Http\Request $request
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function editTopic(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);

        abort_unless($user->group->is_modo, 403);
        $name = $request->input('name');
        $forum_id = $request->input('forum_id');
        $topic->name = $name;
        $topic->forum_id = $forum_id;
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->success('Topic Successfully Edited', 'Yay!', ['options']));
    }

    /**
     * Edit Post Form.
     *
     * @param $slug
     * @param $id
     * @param $postId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postEditForm($slug, $id, $postId)
    {
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = $forum->getCategory();
        $post = Post::findOrFail($postId);

        return view('forum.post_edit', [
            'topic'    => $topic,
            'forum'    => $forum,
            'post'     => $post,
            'category' => $category,
        ]);
    }

    /**
     * Edit A Post In A Topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param $postId
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function postEdit(Request $request, $postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);
        $postUrl = "forums/topic/{$post->topic->slug}.{$post->topic->id}?page={$post->getPageNumber()}#post-{$postId}";

        abort_unless($user->group->is_modo || $post->user_id == $user->id, 403);
        $post->content = $request->input('content');
        $post->save();

        return redirect($postUrl)
            ->with($this->toastr->success('Post Successfully Edited!', 'Yay!', ['options']));
    }

    /**
     * Delete A Post.
     *
     * @param $postId
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function postDelete($postId)
    {
        $user = auth()->user();
        $post = Post::with('topic')->findOrFail($postId);

        abort_unless($user->group->is_modo || $post->user_id == $user->id, 403);
        $post->delete();

        return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])
            ->with($this->toastr->success('This Post Is Now Deleted!', 'Success', ['options']));
    }

    /**
     * Close The Topic.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function closeTopic($slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $topic->first_post_user_id, 403);
        $topic->state = 'close';
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->success('This Topic Is Now Closed!', 'Success', ['options']));
    }

    /**
     * Open The Topic.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function openTopic($slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);

        abort_unless($user->group->is_modo || $user->id === $topic->first_post_user_id, 403);
        $topic->state = 'open';
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->success('This Topic Is Now Open!', 'Success', ['options']));
    }

    /**
     * Delete The Topic and The Posts.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteTopic($slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);

        abort_unless($user->group->is_modo, 403);
        $posts = $topic->posts();
        $posts->delete();
        $topic->delete();

        return redirect()->route('forum_display', ['slug' => $topic->forum->slug, 'id' => $topic->forum->id])
            ->with($this->toastr->error('This Topic Is Now Deleted!', 'Warning', ['options']));
    }

    /**
     * Pin The Topic.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function pinTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->pinned = 1;
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->success('This Topic Is Now Pinned!', 'Success', ['options']));
    }

    /**
     * Unpin The Topic.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function unpinTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->pinned = 0;
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->success('This Topic Is Now Unpinned!', 'Success', ['options']));
    }

    /**
     * Forum Tag System.
     *
     * @param $slug
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function approvedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->approved == 0) {
            $topic->approved = '1';
        } else {
            $topic->approved = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function deniedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->denied == 0) {
            $topic->denied = '1';
        } else {
            $topic->denied = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function solvedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->solved == 0) {
            $topic->solved = '1';
        } else {
            $topic->solved = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function invalidTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->invalid == 0) {
            $topic->invalid = '1';
        } else {
            $topic->invalid = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function bugTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->bug == 0) {
            $topic->bug = '1';
        } else {
            $topic->bug = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function suggestionTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->suggestion == 0) {
            $topic->suggestion = '1';
        } else {
            $topic->suggestion = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function implementedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->implemented == 0) {
            $topic->implemented = '1';
        } else {
            $topic->implemented = '0';
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])
            ->with($this->toastr->info('Label Change Has Been Applied', 'Info', ['options']));
    }
}
