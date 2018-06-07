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
    /**
     * The Attributes That Aren't Mass Assignable
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The Attributes That Are Mass Assignable
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'user_id',
        'chatroom_id'
    ];

    /**
     * Belongs To A User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs To A Chat Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatroom()
    {
        return $this->belongsTo(Chatroom::class);
    }

    /**
     * Parse Content And Return Valid HTML
     *
     * @return string Parsed BBCODE To HTML
     */
    public static function getMessageHtml($message)
    {
        return Bbcode::parse($message);
    }
}