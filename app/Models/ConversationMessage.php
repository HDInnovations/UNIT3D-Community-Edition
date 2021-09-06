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

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    protected $fillable = [
        'user_id',
        'body',
    ];

    public function isOwn()
    {
        return $this->user_id === auth()->id();
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parse Body And Return Valid HTML.
     *
     * @return string Parsed BBCODE To HTML
     */
    public function getBodyHtml()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $linkify->linky($bbcode->parse($this->body, true));
    }
}
