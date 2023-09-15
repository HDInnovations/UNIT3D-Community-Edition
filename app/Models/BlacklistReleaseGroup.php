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

use Illuminate\Database\Eloquent\Model;
use stdClass;

class BlacklistReleaseGroup extends Model
{
    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    public $table = 'blacklist_releasegroups';

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The Attributes That Should Be Cast To Native Types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'json_types' => 'array',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the Type feeds JSON as object.
     */
    public function getObjectReleasegroupAttribute(): stdClass|bool
    {
        // Went with attribute to avoid () calls in views. Uniform ->object_releasegroup vs ->json_types.
        if ($this->json_types) {
            return (object) $this->json_types;
        }

        return false;
    }
}
