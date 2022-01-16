<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonExchange extends Model
{
    use HasFactory;
    use Auditable;

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
     */
    public function getDownloadOptions(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('download', '=', true)
            ->orderBy('value')
            ->get();
    }

    /**
     * @method getUploadOptions
     */
    public function getUploadOptions(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('upload', '=', true)
            ->orderBy('value')
            ->get();
    }

    /**
     * @method getPersonalFreeleechOption
     */
    public function getPersonalFreeleechOption(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('personal_freeleech', '=', true)
            ->orderBy('value')
            ->get();
    }

    /**
     * @method getInviteOption
     */
    public function getInviteOption(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('invite', '=', true)
            ->orderBy('value')
            ->get();
    }

    /**
     * @method getItemCost
     */
    public function getItemCost(int $id): int
    {
        return self::where('id', '=', $id)
            ->get()
            ->toArray()[0]['cost'];
    }
}
