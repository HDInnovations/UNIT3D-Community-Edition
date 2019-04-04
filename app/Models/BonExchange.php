<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Mr.G
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonExchange extends Model
{
    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'bon_exchange';

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The Attributes That Should Be Casted To Native Types.
     *
     * @var array
     */
    protected $casts = [
        'upload'             => 'boolean',
        'download'           => 'boolean',
        'personal_freeleech' => 'boolean',
        'invite'             => 'boolean',
    ];

    /**
     * @method getDownloadOptions
     *
     * @return array
     */
    public function getDownloadOptions()
    {
        return self::where('download', '=', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getUploadOptions
     *
     * @return array
     */
    public function getUploadOptions()
    {
        return self::where('upload', '=', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getPersonalFreeleechOption
     *
     * @return array
     */
    public function getPersonalFreeleechOption()
    {
        return self::where('personal_freeleech', '=', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getInviteOption
     *
     * @return array
     */
    public function getInviteOption()
    {
        return self::where('invite', '=', true)
            ->orderBy('value', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * @method getItemCost
     *
     * @param $id
     * @return int
     */
    public function getItemCost($id)
    {
        return self::where('id', '=', $id)
            ->get()
            ->toArray()[0]['cost'];
    }
}
