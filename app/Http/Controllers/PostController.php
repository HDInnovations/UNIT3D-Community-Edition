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

use App\Achievements\UserMade100Posts;
use App\Achievements\UserMade200Posts;
use App\Achievements\UserMade25Posts;
use App\Achievements\UserMade300Posts;
use App\Achievements\UserMade400Posts;
use App\Achievements\UserMade500Posts;
use App\Achievements\UserMade50Posts;
use App\Achievements\UserMade600Posts;
use App\Achievements\UserMade700Posts;
use App\Achievements\UserMade800Posts;
use App\Achievements\UserMade900Posts;
use App\Achievements\UserMadeFirstPost;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\NewPostTag;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PostControllerTest
 */
class PostController extends Controller
{
    /**
     * PostController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Store A New Post To A Topic.
     */
    public function reply(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = Forum::findOrFail($forum->id);

        // The user has the right to create a post here?
        if (! $category->getPermission()->reply_topic || ($topic->state == 'close' && ! $request->user()->group->is_modo)) {
            return \to_route('forums.index')
                ->withErrors(\trans('forum.reply-topic-error'));
        }

        $post = new Post();
        $post->content = $request->input('content');
        $post->user_id = $user->id;
        $post->topic_id = $topic->id;

        $v = \validator($post->toArray(), [
            'content'  => 'required|min:1',
            'user_id'  => 'required',
            'topic_id' => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('forum_topic', ['id' => $topic->id])
                ->withErrors($v->errors());
        }

        $post->save();

        $topic->last_post_user_id = $user->id;
        $topic->last_post_user_username = $user->username;
        $topic->num_post = Post::where('topic_id', '=', $topic->id)->count();
        $topic->last_reply_at = $post->created_at;
        $topic->save();

        $forum->num_post = $forum->getPostCount($forum->id);
        $forum->num_topic = $forum->getTopicCount($forum->id);
        $forum->last_post_user_id = $user->id;
        $forum->last_post_user_username = $user->username;
        $forum->last_topic_id = $topic->id;
        $forum->last_topic_name = $topic->name;
        $forum->save();

        // Post To Chatbox and Notify Subscribers
        $appurl = \config('app.url');
        $postUrl = \sprintf('%s/forums/topics/%s?page=%s#post-%s', $appurl, $topic->id, $post->getPageNumber(), $post->id);
        $realUrl = \sprintf('/forums/topics/%s?page=%s#post-%s', $topic->id, $post->getPageNumber(), $post->id);
        $profileUrl = \sprintf('%s/users/%s', $appurl, $user->username);

        if (\config('other.staff-forum-notify') && ($forum->id == \config('other.staff-forum-id') || $forum->parent_id == \config('other.staff-forum-id'))) {
            $topic->notifyStaffers($user, $topic, $post);
        } else {
            $this->chatRepository->systemMessage(\sprintf('[url=%s]%s[/url] has left a reply on topic [url=%s]%s[/url]', $profileUrl, $user->username, $postUrl, $topic->name));
            // Notify All Subscribers Of New Reply
            if ($topic->first_user_poster_id != $user->id) {
                $topic->notifyStarter($user, $topic, $post);
            }

            $topic->notifySubscribers($user, $topic, $post);
        }

        // User Tagged Notification
        if ($user->id !== $post->user_id) {
            \preg_match_all('/@([\w\-]+)/', $post->content, $matches);
            $users = User::whereIn('username', $matches[1])->get();
            Notification::send($users, new NewPostTag($post));
        }

        // Achievements
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

        return \redirect()->to($realUrl)
            ->withSuccess(\trans('forum.reply-topic-success'));
    }

    /**
     * Edit Post Form.
     */
    public function postEditForm(int $id, int $postId): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $topic = Topic::findOrFail($id);
        $forum = $topic->forum;
        $category = $forum->getCategory();
        $post = Post::findOrFail($postId);

        return \view('forum.post_edit', [
            'topic'    => $topic,
            'forum'    => $forum,
            'post'     => $post,
            'category' => $category,
        ]);
    }

    /**
     * Edit A Post In A Topic.
     */
    public function postEdit(Request $request, int $postId): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $post = Post::findOrFail($postId);
        $postUrl = \sprintf('forums/topics/%s?page=%s#post-%s', $post->topic->id, $post->getPageNumber(), $postId);

        \abort_unless($user->group->is_modo || $user->id === $post->user_id, 403);
        $post->content = $request->input('content');
        $post->save();

        return \redirect()->to($postUrl)
            ->withSuccess(\trans('forum.edit-post-success'));
    }

    /**
     * Delete A Post.
     *
     * @throws \Exception
     */
    public function postDelete(Request $request, int $postId): \Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $post = Post::with('topic')->findOrFail($postId);

        \abort_unless($user->group->is_modo || $user->id === $post->user_id, 403);
        $post->delete();

        return \to_route('forum_topic', ['id' => $post->topic->id])
            ->withSuccess(\trans('forum.delete-post-success'));
    }
}
