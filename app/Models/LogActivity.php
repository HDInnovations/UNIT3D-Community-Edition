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
 * @author     HDVinnie
 */

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LogActivity
 *
 * @property int $id
 * @property string $subject
 * @property string $url
 * @property string $method
 * @property string $ip
 * @property string|null $agent
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogActivity whereUserId($value)
 * @mixin \Eloquent
 */
class LogActivity extends Model
{
    use Encryptable;

    /**
     * The Attributes That Are Encrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'ip',
    ];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }
}
