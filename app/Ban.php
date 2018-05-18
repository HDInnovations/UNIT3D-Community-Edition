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
 * Class Ban.
 */
class Ban extends Model
{
    public function banneduser()
    {
        return $this->belongsTo(\App\User::class, "owned_by")->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    public function staffuser()
    {
        return $this->belongsTo(\App\User::class, "created_by")->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }
}
