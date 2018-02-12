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

use App\User;
use Illuminate\Database\Eloquent\Model;

class BonExchange extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bon_exchange';

    /**
     * Tells Laravel to not maintain the timestamp columns
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'upload' => 'boolean',
        'download' => 'boolean',
        'personal_freeleech' => 'boolean',
        'invite' => 'boolean',
    ];

    /**
     * @method getDownloadOptions
     *
     * @return array[][]
     */
    public function getDownloadOptions()
    {
        return BonExchange::where('download', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getUploadOptions
     *
     * @return array[][]
     */
    public function getUploadOptions()
    {
        return BonExchange::where('upload', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getPersonalFreeleechOption
     *
     * @return array[][]
     */
    public function getPersonalFreeleechOption()
    {
        return BonExchange::where('personal_freeleech', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getInviteOption
     *
     * @return array[][]
     */
    public function getInviteOption()
    {
        return BonExchange::where('invite', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getItemCost
     *
     * @return integer
     */
    public function getItemCost($id)
    {
        return BonExchange::where('id', '=', $id)
            ->get()
            ->toArray()[0]['cost'];
    }
}
