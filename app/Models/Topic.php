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

namespace App\Models;

use App\Notifications\NewPost;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * Belongs To A Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'first_post_user_id', 'id');
    }

    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Notify Subscribers Of A Topic When New Post Is Made.
     *
     * @return string
     */
    public function notifySubscribers($poster, $topic, $post)
    {
        $subscribers = User::selectRaw('distinct(users.id),max(users.username) as username,max(users.group_id) as group_id')->with('group')->where('users.id', '!=', $poster->id)
            ->join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->leftJoin('user_notifications', 'user_notifications.user_id', '=', 'users.id')
            ->where('subscriptions.topic_id', '=', $topic->id)
            ->whereRaw('(user_notifications.show_subscription_topic = 1 OR user_notifications.show_subscription_topic is null)')
            ->groupBy('users.id')->get();

        foreach ($subscribers as $subscriber) {
            if ($subscriber->acceptsNotification($poster, $subscriber, 'subscription', 'show_subscription_topic')) {
                $subscriber->notify(new NewPost('subscription', $poster, $post));
            }
        }
    }

    /**
     * Does User Have Permission To View Topic.
     *
     * @return string
     */
    public function viewable()
    {
        if (auth()->user()->group->is_modo) {
            return true;
        }

        return $this->forum->getPermission()->read_topic;
    }

    /**
     * Notify Starter When An Action Is Taken.
     *
     * @return bool
     */
    public function notifyStarter($poster, $topic, $post)
    {
        $user = User::find($topic->first_post_user_id);
        if ($user->acceptsNotification(auth()->user(), $user, 'forum', 'show_forum_topic')) {
            $user->notify(new NewPost('topic', $poster, $post));
        }

        return true;
    }

    /**
     * Get Post Number From ID.
     *
     * @param $searchId
     *
     * @return string
     */
    public function postNumberFromId($searchId)
    {
        $count = 0;
        foreach ($this->posts as $post) {
            $count += 1;
            if ($searchId == $post->id) {
                break;
            }
        }

        return $count;
    }
}
