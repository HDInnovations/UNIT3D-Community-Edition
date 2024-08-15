<?php

declare(strict_types=1);

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
 * @property string|null                     $help
 * @property int                             $active
 * @property int                             $is_protected
 * @property int                             $is_nerdbot
 * @property int                             $is_systembot
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Bot extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\BotFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array{name: 'string', cost: 'decimal:2'}
     */
    protected function casts(): array
    {
        return [
            'name' => 'string',
            'cost' => 'decimal:2',
        ];
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
