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
 * App\Models\Gift.
 *
 * @property int                        $id
 * @property int|null                   $sender_id
 * @property int|null                   $recipient_id
 * @property int|null                   $post_id
 * @property string                     $bon
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read Post|null $post
 * @property-read User|null $recipient
 * @property-read User|null $sender
 * @method static \Illuminate\Database\Eloquent\Builder|PostTip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostTip query()
 * @mixin \Eloquent
 */
class PostTip extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function recipient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post, self>
     */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
