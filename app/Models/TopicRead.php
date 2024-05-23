<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TopicRead.
 *
 * @property int $user_id
 * @property int $topic_id
 * @property int $last_read_post_id
 * @property-read Post $lastReadPost
 * @property-read Topic $topic
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TopicRead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicRead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicRead query()
 * @mixin \Eloquent
 */
class TopicRead extends Model
{
    protected $guarded = [];

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Topic, self>
     */
    public function topic(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post, self>
     */
    public function lastReadPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
