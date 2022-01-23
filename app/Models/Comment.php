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

use App\Events\TicketWentStale;
use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

class Comment extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Torrent.
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }

    /**
     * Belongs To A Article.
     */
    public function article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Belongs To A Request.
     */
    public function request(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TorrentRequest::class, 'requests_id', 'id');
    }

    /**
     * Belongs To A Playlist.
     */
    public function playlist(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    /**
     * Belongs To A Ticket.
     */
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Set The Comments Content After Its Been Purified.
     */
    public function setContentAttribute(string $value): void
    {
        $this->attributes['content'] = \htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->content, true));
    }

    /**
     * Nootify Staff There Is Stale Tickets.
     */
    public static function checkForStale(Ticket $ticket): void
    {
        if (empty($ticket->reminded_at) || \strtotime($ticket->reminded_at) < \strtotime('+ 3 days')) {
            $last_comment = $ticket->comments()->orderByDesc('id')->first();

            if (\property_exists($last_comment, 'id') && $last_comment->id !== null && ! $last_comment->user->is_modo && \strtotime($last_comment->created_at) < \strtotime('- 3 days')) {
                \event(new TicketWentStale($last_comment->ticket));
            }
        }
    }
}
