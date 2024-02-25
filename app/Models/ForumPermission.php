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

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Permission.
 *
 * @property int  $id
 * @property int  $forum_id
 * @property int  $group_id
 * @property bool $read_topic
 * @property bool $reply_topic
 * @property bool $start_topic
 */
class ForumPermission extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * Tells Laravel To Not Maintain The Timestamp Columns.
     *
     * @var bool
     */
    public $timestamps = false;

    public $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_topic'  => 'boolean',
        'reply_topic' => 'boolean',
        'start_topic' => 'boolean',
    ];

    /**
     * Belongs To A Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Group, self>
     */
    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Belongs To A Forum.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Forum, self>
     */
    public function forum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }
}
