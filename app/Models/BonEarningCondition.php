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
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonEarning.
 *
 * @property int                                                                                                                      $id
 * @property int                                                                                                                      $bon_earning_id
 * @property '1'|'age'|'size'|'seeders'|'leechers'|'times_completed'|'seedtime'|'internal'|'personal_release'|'type_id'|'connectable' $operand1
 * @property '<'|'>'|'<='|'>='|'='|'!='                                                                                               $operator
 * @property float                                                                                                                    $operand2
 */
class BonEarningCondition extends Model
{
    /**
     * Indicates If The Model Should Be Timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Belongs To A Bon Earning.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<BonEarning, $this>
     */
    public function bonEarning(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BonEarning::class);
    }
}
