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
use App\Helpers\Bbcode;

class Message extends Model
{
    protected $table = 'messages';

    protected $with = ['user'];

    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id',
        'chatroom_id'
    ];

    /**
     * A message belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * A message belongs to a chatroom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatroom()
    {
        return $this->belongsTo(\App\Chatroom::class);
    }

    /**
     * Parse content and return valid HTML
     *
     */
    public static function getMessageHtml($message)
    {
        return Bbcode::parse($message);
    }
}