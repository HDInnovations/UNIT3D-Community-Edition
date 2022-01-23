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

namespace App\Models;

use App\Notifications\NewPost;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    use Auditable;

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    /**
     * Belongs To A Forum.
     */
    public function forum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'first_post_user_id', 'id');
    }

    /**
     * Has Many Posts.
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Has Many Subscriptions.
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Notify Subscribers Of A Topic When New Post Is Made.
     */
    public function notifySubscribers($poster, $topic, $post): void
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
     * Notify Staffers When New Staff Post Is Made.
     */
    public function notifyStaffers($poster, $topic, $post): void
    {
        $staffers = User::leftJoin('groups', 'users.group_id', '=', 'groups.id')
            ->select('users.id')
            ->where('users.id', '<>', $poster->id)
            ->where('groups.is_modo', 1)
            ->get();

        foreach ($staffers as $staffer) {
            $staffer->notify(new NewPost('staff', $poster, $post));
        }
    }

    /**
     * Does User Have Permission To View Topic.
     */
    public function viewable(): bool
    {
        if (\auth()->user()->group->is_modo) {
            return true;
        }

        return $this->forum->getPermission()->read_topic;
    }

    /**
     * Notify Starter When An Action Is Taken.
     */
    public function notifyStarter($poster, $topic, $post): bool
    {
        $user = User::find($topic->first_post_user_id);
        if ($user->acceptsNotification(\auth()->user(), $user, 'forum', 'show_forum_topic')) {
            $user->notify(new NewPost('topic', $poster, $post));
        }

        return true;
    }

    /**
     * Get Post Number From ID.
     */
    public function postNumberFromId($searchId): int
    {
        $count = 0;
        foreach ($this->posts as $post) {
            $count++;
            if ($searchId == $post->id) {
                break;
            }
        }

        return $count;
    }
}
