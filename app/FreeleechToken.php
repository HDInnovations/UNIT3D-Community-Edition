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

use Illuminate\Database\Eloquent\Model;

class freeleechToken extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'freeleech_tokens';

    /**
     * Mass assignment fields
     *
     */
    protected $fillable = ['user_id', 'torrent_id'];
}
