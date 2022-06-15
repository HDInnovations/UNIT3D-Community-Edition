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
    use HasFactory;
    use Auditable;

    /**
     * Has Many Topic.
     */
    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Sub Topics.
     */
    public function sub_topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $children = $this->forums->pluck('id')->toArray();
        if (\is_array($children)) {
            return $this->hasMany(Topic::class)->orWhereIn('topics.forum_id', $children);
        }

        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Sub Forums.
     */
    public function forums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Has Many Subscribed Topics.
     */
    public function subscription_topics(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        if (\auth()->user() !== null) {
            $id = $this->id;
            $subscriptions = \auth()->user()->subscriptions->where('topic_id', '>', '0')->pluck('topic_id')->toArray();

            return $this->hasMany(Topic::class)->where(function ($query) use ($id, $subscriptions) {
                $query->whereIntegerInRaw('topics.id', [$id])->orWhereIntegerInRaw('topics.id', $subscriptions);
            });
        }

        return $this->hasMany(Topic::class, 'id', 'topic_id');
    }

    /**
     * Has Many Subscriptions.
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class, 'forum_id', 'id');
    }

    /**
     * Has Many Permissions.
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Notify Subscribers Of A Forum When New Topic Is Made.
     */
    public function notifySubscribers($poster, $topic): void
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
    public function notifyStaffers($poster, $topic): void
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
     * Returns A Table With The Forums In The Category.
     */
    public function getForumsInCategory()
    {
        return self::where('parent_id', '=', $this->id)->get();
    }

    /**
     * Returns A Table With The Forums In The Category.
     */
    public function getForumsInCategoryById(int $forumId)
    {
        return self::where('parent_id', '=', $forumId)->get();
    }

    /**
     * Returns The Category In Which The Forum Is Located.
     */
    public function getCategory()
    {
        return self::find($this->parent_id);
    }

    /**
     * Count The Number Of Posts In The Forum.
     */
    public function getPostCount(int $forumId): float|int
    {
        $topics = self::find($forumId)->topics;
        $count = 0;
        foreach ($topics as $t) {
            $count += $t->posts()->count();
        }

        return $count;
    }

    /**
     * Count The Number Of Topics In The Forum.
     */
    public function getTopicCount(int $forumId): int
    {
        $forum = self::find($forumId);

        return Topic::where('forum_id', '=', $forum->id)->count();
    }

    /**
     * Returns The Permission Field.
     */
    public function getPermission(): object
    {
        $group = \auth()->check() ? \auth()->user()->group : Group::where('slug', 'guest')->first();

        return $group->permissions->where('forum_id', $this->id)->first();
    }
}
