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

class Report extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "reports";

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = [
        'type', 'reporter_id', 'staff_id', 'title', 'message', 'solved'
    ];

    /**
     * Belongs to User
     *
     */
    public function reportuser()
    {
        return $this->belongsTo(\App\User::class, "reporter_id");
    }

    public function staffuser()
    {
        return $this->belongsTo(\App\User::class, "staff_id");
    }
}
