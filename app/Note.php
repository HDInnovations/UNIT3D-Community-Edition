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

class Note extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "user_notes";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'user_id', 'staff_id', 'message'
    ];

    /**
     * Belongs to User
     *
     */
    public function noteduser()
    {
        return $this->belongsTo(\App\User::class, "user_id");
    }

    public function staffuser()
    {
        return $this->belongsTo(\App\User::class, "staff_id");
    }
}
