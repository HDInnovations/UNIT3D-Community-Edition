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

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Ban.
 */
class Ban extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ban';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owned_by',
        'created_by',
        'ban_reason',
        'unban_reason',
    ];

    /**
     * Rules For Validation
     *
     */
    public $rules = [
        'owned_by' => 'required',
        'created_by' => 'required',
        'ban_reason' => 'required',
        'unban_reason' => 'required',
    ];

    public function banneduser()
    {
        return $this->belongsTo(\App\User::class, "owned_by");
    }

    public function staffuser()
    {
        return $this->belongsTo(\App\User::class, "created_by");
    }
}
