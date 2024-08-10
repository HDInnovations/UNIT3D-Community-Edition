<?php

declare(strict_types=1);

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

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Like.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $post_id
 * @property bool|null                       $like
 * @property bool|null                       $dislike
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Like extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\LikeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array{like: 'bool', dislike: 'bool'}
     */
    protected function casts(): array
    {
        return [
            'like'    => 'bool',
            'dislike' => 'bool',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Post, $this>
     */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
