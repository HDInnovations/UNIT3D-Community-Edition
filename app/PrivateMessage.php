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

class PrivateMessage extends Model
{
    /**
     * PM Belongs To User
     */
    public function sender()
    {
        return $this->belongsTo(User::class, "sender_id")->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * PM Belongs To User
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, "receiver_id")->withDefault([
            'username' => 'System',
            'id' => '1'
        ]);
    }

    /**
     * Parse Message And Return Valid HTML
     */
    public function getMessageHtml()
    {
        return Bbcode::parse($this->message);
    }
}
