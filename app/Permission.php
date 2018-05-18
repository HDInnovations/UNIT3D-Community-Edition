<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Permission of the forums
 *
 */
class Permission extends Model
{

    public $timestamps = false;

    /**
     * Belongs to group
     *
     */
    public function group()
    {
        return $this->belongsTo(\App\Group::class);
    }

    /**
     * Belongs to Forum
     *
     */
    public function forum()
    {
        return $this->belongsTo(\App\Forum::class);
    }
}
