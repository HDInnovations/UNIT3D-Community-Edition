<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://choosealicense.com/licenses/gpl-3.0/  GNU General Public License v3.0
 * @author     HDVinnie
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;
use Decoda\Decoda;

class PrivateMessage extends Model
{
    protected $fillable = [
        'sender_id', 'reciever_id', 'subject', 'message', 'read', 'related_to'
    ];

    /**
     * PM belongs to User
     *
     */
    public function sender()
    {
        return $this->belongsTo(\App\User::class, "sender_id");
    }

    /**
     * PM belongs to User
     *
     */
    public function receiver()
    {
        return $this->belongsTo(\App\User::class, "reciever_id");
    }

    /**
     * Parse message and return valid HTML
     *
     */
    public function getMessageHtml()
    {
        $code = new Decoda($this->message);
        $code->defaults();
        $code->setXhtml(false);
        $code->setStrict(false);
        $code->setLineBreaks(true);
        return $code->parse();
    }
}
