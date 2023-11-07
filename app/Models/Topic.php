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
    use Auditable;
    use HasFactory;

    protected $casts = [
        'last_reply_at' => 'datetime',
        'pinned'        => 'boolean',
        'approved'      => 'boolean',
        'denied'        => 'boolean',
        'solved'        => 'boolean',
        'invalid'       => 'boolean',
        'bug'           => 'boolean',
        'suggestion'    => 'boolean',
        'implemented'   => 'boolean',
    ];

    protected $fillable = [
        'name',
        'state',
        'first_post_user_id',
        'last_post_user_id',
        'first_post_user_username',
        'last_post_user_username',
        'views',
        'pinned',
        'forum_id',
        'num_post',
        'last_reply_at',
    ];

    /**
     * Belongs To A Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Forum, self>
     */
    public function forum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'first_post_user_id', 'id');
    }

    /**
     * Has Many Posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Post>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subscription>
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Has One Permissions through Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Permission>
     */
    public function forumPermissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class, 'forum_id', 'forum_id');
    }

    /**
     * Belongs to Many Subscribed Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function subscribedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, Subscription::class);
    }

    /**
     * Latest post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Post>
     */
    public function latestPost(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Post::class)->latestOfMany();
    }

    /**
     * Latest poster.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function latestPoster(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'last_post_user_id');
    }

    /**
     * Notify Subscribers Of A Topic When New Post Is Made.
     */
    public function notifySubscribers(User $poster, Topic $topic, Post $post): void
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
    public function notifyStaffers(User $poster, Topic $topic, Post $post): void
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
        if (auth()->user()->group->is_modo) {
            return true;
        }

        return $this->forum->getPermission()->read_topic;
    }

    /**
     * Notify Starter When An Action Is Taken.
     */
    public function notifyStarter(User $poster, Topic $topic, Post $post): bool
    {
        $user = User::find($topic->first_post_user_id);

        if ($user->acceptsNotification(auth()->user(), $user, 'forum', 'show_forum_topic')) {
            $user->notify(new NewPost('topic', $poster, $post));
        }

        return true;
    }
}
