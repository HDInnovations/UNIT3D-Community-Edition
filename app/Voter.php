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

class Voter extends Model
{

    protected $fillable = [
        'poll_id',
        'user_id',
        'ip_address'
    ];

    public function poll()
    {
        return $this->belongsTo(\App\Poll::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
