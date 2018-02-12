<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "warnings";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'user_id', 'warned_by', 'torrent', 'reason', 'expires_on', 'active'
    ];

    /**
     * Belongs to Torrent
     *
     */
    public function torrenttitle()
    {
        return $this->belongsTo(\App\Torrent::class, 'torrent');
    }

    public function warneduser()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function staffuser()
    {
        return $this->belongsTo(\App\User::class, 'warned_by');
    }
}
