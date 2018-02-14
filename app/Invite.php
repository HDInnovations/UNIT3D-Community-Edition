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

class Invite extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "invites";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'user_id', 'email', 'code', 'expires_on', 'accepted_by', 'accepted_at', 'custom'
    ];

    /**
     * Belongs to User
     *
     *
     */
    public function sender()
    {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    /**
     * Belongs to User
     *
     *
     */
    public function reciever()
    {
        return $this->belongsTo(\App\User::class, 'accepted_by');
    }
}
