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

use App\Notifications\NewTopic;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at'];

    /**
     * Has Many Topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Topic>
     */
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Sub Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Topic>
     */
    public function sub_topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $children = $this->forums->pluck('id')->toArray();

        return $this->hasMany(Topic::class)->orWhereIn('topics.forum_id', $children);
    }

    /**
     * Has Many Sub Forums.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<self>
     */
    public function forums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Returns The Category In Which The Forum Is Located.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * All posts inside the forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Post>
     */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Post::class, Topic::class);
    }

    /**
     * Latest topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Topic>
     */
    public function lastRepliedTopic(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Topic::class)->ofMany('last_reply_at', 'max');
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
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Subscription>
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class, 'forum_id', 'id');
    }

    /**
     * Has Many Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Permission>
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Belongs To Many Subscribed Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function subscribedUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, Subscription::class);
    }

    /**
     * Notify Subscribers Of A Forum When New Topic Is Made.
     */
    public function notifySubscribers(User $poster, Topic $topic): void
    {
        $subscribers = User::selectRaw('distinct(users.id),max(users.username) as username,max(users.group_id) as group_id')->with('group')->where('users.id', '!=', $topic->first_post_user_id)
            ->join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->leftJoin('user_notifications', 'user_notifications.user_id', '=', 'users.id')
            ->where('subscriptions.forum_id', '=', $topic->forum_id)
            ->whereRaw('(user_notifications.show_subscription_forum = 1 OR user_notifications.show_subscription_forum is null)')
            ->groupBy('users.id')->get();

        foreach ($subscribers as $subscriber) {
            if ($subscriber->acceptsNotification($poster, $subscriber, 'subscription', 'show_subscription_forum')) {
                $subscriber->notify(new NewTopic('forum', $poster, $topic));
            }
        }
    }

    /**
     * Notify Staffers When New Staff Topic Is Made.
     */
    public function notifyStaffers(User $poster, Topic $topic): void
    {
        $staffers = User::leftJoin('groups', 'users.group_id', '=', 'groups.id')
            ->select('users.id')
            ->where('users.id', '<>', $poster->id)
            ->where('groups.is_modo', 1)
            ->get();

        foreach ($staffers as $staffer) {
            $staffer->notify(new NewTopic('staff', $poster, $topic));
        }
    }

    /**
     * Returns The Permission Field.
     */
    public function getPermission(): ?Permission
    {
        $group = auth()->check() ? auth()->user()->group : Group::where('slug', 'guest')->first();

        return $group->permissions->where('forum_id', $this->id)->first();
    }
}
