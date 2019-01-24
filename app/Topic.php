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
    public function notifySubscribers($post)
    {
        $this->subscriptions->where('user_id', '!=', $post->user_id)->each->notifyTopic($post);
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
    public function notifyStarter($type, $payload)
    {
        User::find($this->first_post_user_id)->notify(new NewPost('topic', $payload));

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
