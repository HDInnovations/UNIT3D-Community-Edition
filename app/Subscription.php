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
use App\Notifications\NewPost;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Topic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

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
     * Notify Subscribers Of A Topic When New Post Is Made.
     *
     * @return string
     */
    public function notifyTopic($post)
    {
        User::find($this->user_id)->notify(new NewPost('topic',$post));
    }

    /**
     * Notify Subscribers Of A Forum When New Topic Is Made.
     *
     * @return string
     */
    public function notifyForum($topic)
    {
        User::find($this->user_id)->notify(new NewTopic('forum',$topic));
    }
}
