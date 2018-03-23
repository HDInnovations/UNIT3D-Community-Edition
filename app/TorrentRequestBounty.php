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

class TorrentRequestBounty extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_bounty';

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = ['user_id', 'seedbonus', 'requests_id'];

    /**
     * Belongs to This User
     *
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Belongs to Request
     *
     */
    public function request()
    {
        return $this->belongsTo(\App\TorrentRequest::class);
    }
}
