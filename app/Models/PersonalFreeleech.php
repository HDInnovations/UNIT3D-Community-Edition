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

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PersonalFreeleech whereUserId($value)
 * @mixin \Eloquent
 */
class PersonalFreeleech extends Model
{
    /**
     * The Database Table Used By The Model.
     *
     * @var string
     */
    protected $table = 'personal_freeleech';
}
