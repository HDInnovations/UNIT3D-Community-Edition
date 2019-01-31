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

namespace App;

use App\Notifications\NewTopic;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    /**
     * Has Many Topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Sub Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sub_topics()
    {
        $children = $this->forums->pluck('id')->toArray();
        if (is_array($children)) {
            return $this->hasMany(Topic::class)->orWhereIn('topics.forum_id', $children);
        }

        return $this->hasMany(Topic::class);
    }

    /**
     * Has Many Sub Forums.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forums()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Has Many Subscribed Topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscription_topics()
    {
        if (auth()->user()) {
            $id = $this->id;
            $subscriptions = auth()->user()->subscriptions->where('topic_id', '>', '0')->pluck('topic_id')->toArray();

            return $this->hasMany(Topic::class)->where(function ($query) use ($id, $subscriptions) {
                $query->whereIn('topics.id', [$id])->orWhereIn('topics.id', $subscriptions);
            });
        }

        return $this->hasMany(Topic::class, 'id', 'topic_id');
    }

    /**
     * Has Many Subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'forum_id', 'id');
    }

    /**
     * Has Many Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Notify Subscribers Of A Forum When New Topic Is Made.
     *
     * @return string
     */
    public function notifySubscribers($poster, $topic)
    {
        $subscribers = User::distinct()->selectRaw('users.*')->with('group')->where('users.id', '!=', $topic->first_post_user_id)
            ->join('subscriptions', 'subscriptions.user_id', '=', 'users.id')
            ->leftJoin('user_notifications', 'user_notifications.user_id', '=', 'users.id')
            ->where('subscriptions.forum_id', '=', $topic->forum_id)
            ->where('user_notifications.show_subscription_forum', '=', '1')
            ->groupBy('users.id')->get();

        foreach ($subscribers as $subscriber) {
            if ($subscriber->acceptsNotification($poster, $subscriber, 'subscription', 'show_subscription_forum')) {
                $subscriber->notify(new NewTopic('forum', $poster, $topic));
            }
        }
    }

    /**
     * Returns A Table With The Forums In The Category.
     *
     * @return string
     */
    public function getForumsInCategory()
    {
        return self::where('parent_id', '=', $this->id)->get();
    }

    /**
     * Returns A Table With The Forums In The Category.
     *
     * @return string
     */
    public function getForumsInCategoryById($forumId)
    {
        return self::where('parent_id', '=', $forumId)->get();
    }

    /**
     * Returns The Category Nn Which The Forum Is Located.
     *
     * @return string
     */
    public function getCategory()
    {
        return self::find($this->parent_id);
    }

    /**
     * Count The Number Of Posts In The Forum.
     *
     * @return string
     */
    public function getPostCount($forumId)
    {
        $forum = self::find($forumId);
        $topics = $forum->topics;
        $count = 0;
        foreach ($topics as $t) {
            $count += $t->posts()->count();
        }

        return $count;
    }

    /**
     * Count The Number Of Topics In The Forum.
     *
     * @return string
     */
    public function getTopicCount($forumId)
    {
        $forum = self::find($forumId);

        return Topic::where('forum_id', '=', $forum->id)->count();
    }

    /**
     * Returns The Permission Field.
     *
     * @return string
     */
    public function getPermission()
    {
        if (auth()->check()) {
            $group = auth()->user()->group;
        } else {
            $group = Group::find(2);
        }

        return Permission::whereRaw('forum_id = ? AND group_id = ?', [$this->id, $group->id])->first();
    }
}
