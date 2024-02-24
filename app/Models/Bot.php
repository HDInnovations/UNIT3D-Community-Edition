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

/**
 * App\Models\Bot.
 *
 * @property int                             $id
 * @property int                             $position
 * @property string                          $name
 * @property string                          $command
 * @property string|null                     $color
 * @property string|null                     $icon
 * @property string|null                     $emoji
 * @property string|null                     $info
 * @property string|null                     $about
 * @property string|null                     $help
 * @property int                             $active
 * @property int                             $is_protected
 * @property int                             $is_triviabot
 * @property int                             $is_nerdbot
 * @property int                             $is_systembot
 * @property int                             $is_casinobot
 * @property int                             $is_betbot
 * @property int                             $uploaded
 * @property int                             $downloaded
 * @property int                             $fl_tokens
 * @property float                           $seedbonus
 * @property int                             $invites
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Bot extends Model
{
    use Auditable;
    use HasFactory;

    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The Attributes That Should Be Cast To Native Types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
