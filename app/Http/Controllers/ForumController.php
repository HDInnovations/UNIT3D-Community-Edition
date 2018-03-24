<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Forum;
use App\Post;
use App\Topic;
use App\User;
use App\Shoutbox;
use App\Like;
use App\Achievements\UserMadeFirstPost;
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
use App\Mail\NewReply;
use Decoda\Decoda;
use \Toastr;

class ForumController extends Controller
{

    /**
     * Search for topics
     *
     * @access public
     * @return View page.torrents
     *
     */
    public function search(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('name');
        $results = Topic::where([
            ['name', 'like', '%' . $request->input('name') . '%'],
        ])->latest()->paginate(25);

        $results->setPath('?name=' . $request->input('name'));

        return view('forum.results', ['results' => $results, 'user' => $user]);
    }

    /**
     * Display the forum homepage
     *
     */
    public function index()
    {
        $categories = Forum::oldest('position')->get();
        // Total Forums Count
        $num_forums = Forum::all()->count();
        // Total Posts Count
        $num_posts = Post::all()->count();
        // Total Topics Count
        $num_topics = Topic::all()->count();
        return view('forum.index', ['categories' => $categories, 'num_posts' => $num_posts, 'num_forums' => $num_forums, 'num_topics' => $num_topics]);
    }

    /**
     * Displays the requested category
     *
     * @access public
     * @param $slug
     * @param $id
     * @return void
     */
    public function category($slug, $id)
    {
        $category = Forum::findOrFail($id);
        if ($category->getPermission()->show_forum != true) {
            return redirect()->route('forum_index')->with(Toastr::error('You Do Not Have Access To This Category!', 'Whoops!', ['options']));
        }
        return view('forum.category', ['c' => $category]);
    }

    /**
     * Shows forums and topics inside
     *
     * @access public
     * @param $slug
     * @param $id
     * @return View forum.display
     */
    public function display($slug, $id)
    {
        // Find the topic
        $forum = Forum::findOrFail($id);
        // Check if this is a category or forum
        if ($forum->parent_id == 0) {
            return redirect()->route('forum_category', ['slug' => $forum->slug, 'id' => $forum->id]);
        }
        $category = Forum::findOrFail($forum->parent_id);
        // Check if the user has permission to view the forum
        if ($category->getPermission()->show_forum != true) {
            return redirect()->route('forum_index')->with(Toastr::error('You Do Not Have Access To This Forum!', 'Whoops!', ['options']));
        }

        // Fetch topics->posts in descending order
        $topics = $forum->topics()->latest('pinned')->latest('last_reply_at')->latest()->paginate(25);

        return view('forum.display', ['forum' => $forum, 'topics' => $topics, 'category' => $category]);
    }

    /**
     * Show the topic
     *
     * @access public
     * @param $slug
     * @param $id
     * @return forum.topic
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
        $posts = $topic->posts()->paginate(25);

        // First post
        $firstPost = Post::where('topic_id', $topic->id)->first();

        // The user can post a topic here ?
        if ($category->getPermission()->read_topic != true) {
            // Redirect him to the forum index
            return redirect()->route('forum_index')->with(Toastr::error('You Do Not Have Access To Read This Topic!', 'Whoops!', ['options']));
        }

        // Increment view
        $topic->views++;
        $topic->save();

        return view('forum.topic', ['topic' => $topic, 'forum' => $forum, 'category' => $category, 'posts' => $posts, 'firstPost' => $firstPost]);
    }

    /**
     * Add a reply to a topic
     *
     * @param $slug
     * @param $id
     */
    public function reply(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = $forum->getCategory();

        // The user has the right to create a topic here?
        if (!$category->getPermission()->reply_topic || ($topic->state == "close" && !auth()->user()->group->is_modo)) {
            return redirect()->route('forum_index')->with(Toastr::error('You Cannot Reply To This Topic!', 'Whoops!', ['options']));
        }

        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = $user->id;
        $post->topic_id = $topic->id;

        $v = validator($post->toArray(), [
            'content' => 'required',
            'user_id' => 'required',
            'topic_id' => 'required'
        ]);
        if ($v->passes()) {
            // Save the reply
            $post->save();
            // Save last post user data to topic table
            $topic->last_post_user_id = $user->id;
            $topic->last_post_user_username = $user->username;
            // Count post in topic
            $topic->num_post = Post::where('topic_id', $topic->id)->count();
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

            // Find the user who initated the topic
            $topicCreator = User::findOrFail($topic->first_post_user_id);

            // Post To ShoutBox
            $appurl = config('app.url');
            Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has left a reply on topic [url={$appurl}/forums/topic/" . $topic->slug . "." . $topic->id . "?page={$post->getPageNumber()}#post-{$post->id}" . "]" . $topic->name . "[/url]"]);
            cache()->forget('shoutbox_messages');

            // Mail Topic Creator Of New Reply
            if ($post->user_id != $topic->first_post_user_id) {
                Mail::to($topicCreator->email)->send(new NewReply($user, $topic));
            }

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

            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('Post Successfully Posted', 'Yay!', ['options']));
        } else {
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::error('You Cannot Reply To This Topic!', 'Whoops!', ['options']));
        }
    }

    /**
     * Create a new topic in the forum
     *
     * @param $slug
     * @param $id
     */
    public function newTopic(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $forum = Forum::findOrFail($id);
        $category = $forum->getCategory();

        // The user has the right to create a topic here?
        if ($category->getPermission()->start_topic != true) {
            return redirect()->route('forum_index')->with(Toastr::error('You Cannot Start A New Topic Here!', 'Whoops!', ['options']));
        }

        // Preview The Post
        $parsedContent = null;
        if ($request->isMethod('POST') && $request->input('preview') == true) {
            $code = new Decoda($request->input('content'));
            $code->defaults();
            $code->setXhtml(false);
            $code->setStrict(false);
            $code->setLineBreaks(true);
            $parsedContent = $code->parse();
        }

        if ($request->isMethod('POST') && $request->input('post') == true) {
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
            $v = validator($topic->toArray(), $topic->rules);
            if ($v->passes()) {
                $topic->save();

                $post = new Post();
                $post->content = $request->input('content');
                $post->user_id = $user->id;
                $post->topic_id = $topic->id;
                $v = validator($post->toArray(), $post->rules);
                if ($v->passes()) {
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

                    // Post To ShoutBox
                    $appurl = config('app.url');
                    Shoutbox::create(['user' => "1", 'mentions' => "1", 'message' => "User [url={$appurl}/" . $user->username . "." . $user->id . "]" . $user->username . "[/url] has created a new topic [url={$appurl}/forums/topic/" . $topic->slug . "." . $topic->id . "]" . $topic->name . "[/url]"]);
                    cache()->forget('shoutbox_messages');

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

                    return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]);
                } else {
                    // Unable to save the first post doc delete the topic
                    Toastr::error('You Did Not Meet All The Requirments For Creating A Yopic!', 'Whoops!', ['options']);
                    $topic->delete();
                }
            } else {
                Toastr::error('A Error Has Occured With This Topic! Please Try Again!', 'Whoops!', ['options']);
            }
        }
        return view('forum.new_topic', ['forum' => $forum, 'category' => $category, 'parsedContent' => $parsedContent, 'title' => $request->input('title'), 'content' => $request->input('content')]);
    }

    /**
     * Edit topic in the forum
     *
     * @param $slug
     * @param $id
     */
    public function editTopic(Request $request, $slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        $categories = Forum::where('parent_id', '!=', 0)->get();

        if ($user->group->is_modo) {
            if ($request->isMethod('POST')) {
                $name = $request->input('name');
                $forum_id = $request->input('forum_id');

                $topic->name = $name;
                $topic->forum_id = $forum_id;
                $topic->save();

                return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('Topic Successfully Edited', 'Yay!', ['options']));
            } else {
                return view('forum.edit_topic', ['topic' => $topic, 'categories' => $categories]);
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Edit user's post
     *
     * @param $slug
     * @param $id
     * @param $postId
     */
    public function postEdit(Request $request, $slug, $id, $postId)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = $forum->getCategory();
        $post = Post::findOrFail($postId);
        $parsedContent = null;

        if ($user->group->is_modo == false) {
            if ($post->user_id != $user->id) {
                return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::error('You Cannot Edit This!', 'Whoops!', ['options']));
            }
        }

        // Post preview
        if ($request->isMethod('POST') && $request->input('preview') == true) {
            $post->content = $request->input('content');
            $code = new Decoda($post->content);
            $code->defaults();
            $parsedContent = $code->parse();
        }

        if ($request->isMethod('POST') && $request->input('post') == true) {
            $post->content = $request->input('content');
            $post->save();
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]);
        }
        return view('forum.post_edit', ['user' => $user, 'topic' => $topic, 'forum' => $forum, 'post' => $post, 'category' => $category, 'parsedContent' => $parsedContent]);
    }

    /**
     * Delete user's post
     *
     * @param $slug
     * @param $id
     * @param $postId
     */
    public function postDelete($slug, $id, $postId)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        $post = Post::findOrFail($postId);

        if ($user->group->is_modo == false) {
            if ($post->user_id != $user->id) {
                return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::error('You Cannot Delete This!', 'Whoops!', ['options']));
            }
        }
        $post->delete();
        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('This Post Is Now Deleted!', 'Success', ['options']));
    }


    /**
     * Close The Topic
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function closeTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->state = "close";
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::error('This Topic Is Now Closed!', 'Warning', ['options']));
    }

    /**
     * Open The Topic
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function openTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->state = "open";
        $topic->save();
        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('This Topic Is Now Open!', 'Success', ['options']));
    }

    /**
     * Delete the topic and the posts
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function deleteTopic($slug, $id)
    {
        $user = auth()->user();
        $topic = Topic::findOrFail($id);
        if ($user->group->is_modo == true) {
            $posts = $topic->posts();
            $posts->delete();
            $topic->delete();
            return redirect()->route('forum_display', ['slug' => $topic->forum->slug, 'id' => $topic->forum->id])->with(Toastr::error('This Topic Is Now Deleted!', 'Warning', ['options']));
        } else {
            return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::error('You Do Not Have Access To Perform This Function!', 'Warning', ['options']));
        }
    }

    /**
     * Pin The Topic
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function pinTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->pinned = 1;
        $topic->save();
        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('This Topic Is Now Pinned!', 'Success', ['options']));
    }

    /**
     * Unpin The Topic
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function unpinTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->pinned = 0;
        $topic->save();
        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::success('This Topic Is Now Unpinned!', 'Success', ['options']));
    }

    /**
     * Forum Tag System
     *
     * @access public
     * @param $slug
     * @param $id
     * @return Redirect to forum_topic
     */
    public function approvedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->approved == 0) {
            $topic->approved = "1";
        } else {
            $topic->approved = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function deniedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->denied == 0) {
            $topic->denied = "1";
        } else {
            $topic->denied = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function solvedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->solved == 0) {
            $topic->solved = "1";
        } else {
            $topic->solved = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function invalidTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->invalid == 0) {
            $topic->invalid = "1";
        } else {
            $topic->invalid = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function bugTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->bug == 0) {
            $topic->bug = "1";
        } else {
            $topic->bug = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function suggestionTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->suggestion == 0) {
            $topic->suggestion = "1";
        } else {
            $topic->suggestion = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function implementedTopic($slug, $id)
    {
        $topic = Topic::findOrFail($id);
        if ($topic->implemented == 0) {
            $topic->implemented = "1";
        } else {
            $topic->implemented = "0";
        }
        $topic->save();

        return redirect()->route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id])->with(Toastr::info('Label Change Has Been Applied', 'Info', ['options']));
    }

    public function likePost($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();
        $like = $user->likes()->where('post_id', $post->id)->where('like', 1)->first();
        $dislike = $user->likes()->where('post_id', $post->id)->where('dislike', 1)->first();

        if ($like || $dislike) {
            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::error('You have already liked/disliked this post!', 'Bro', ['options']));
        } elseif ($user->id == $post->user_id) {
            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::error('You cannot like your own post!', 'Umm', ['options']));
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->like = 1;
            $new->save();

            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::success('Like Successfully Applied!', 'Yay', ['options']));
        }
    }

    public function dislikePost($postId)
    {
        $post = Post::findOrFail($postId);
        $user = auth()->user();
        $like = $user->likes()->where('post_id', $post->id)->where('like', 1)->first();
        $dislike = $user->likes()->where('post_id', $post->id)->where('dislike', 1)->first();

        if ($like || $dislike) {
            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::error('You have already liked/disliked this post!', 'Bro', ['options']));
        } elseif ($user->id == $post->user_id) {
            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::error('You cannot like your own post!', 'Umm', ['options']));
        } else {
            $new = new Like();
            $new->user_id = $user->id;
            $new->post_id = $post->id;
            $new->dislike = 1;
            $new->save();

            return redirect()->route('forum_topic', ['slug' => $post->topic->slug, 'id' => $post->topic->id])->with(Toastr::success('Dislike Successfully Applied!', 'Yay', ['options']));
        }
    }
}
