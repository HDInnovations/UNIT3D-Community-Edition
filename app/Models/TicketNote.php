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

use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

/**
 * App\Models\TicketNote.
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property int                             $ticket_id
 * @property string                          $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TicketNote extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs To A Ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Ticket, $this>
     */
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Set Message After It's Been Purified.
     */
    public function setMessageAttribute(?string $value): void
    {
        $this->attributes['message'] = $value === null ? null : htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Message And Return Valid HTML.
     */
    public function getMessageHtml(): string
    {
        return (new Linkify())->linky($this->message);
    }
}
