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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonExchange extends Model
{
    use HasFactory;

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
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];
}
