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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content',
        'user_id',
        'anon',
    ];

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

    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->oldest();
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function scopeParent(Builder $builder): void
    {
        $builder->whereNull('parent_id');
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
            $last_comment = $ticket->comments()->latest('id')->first();

            if (\property_exists($last_comment, 'id') && $last_comment->id !== null && ! $last_comment->user->is_modo && \strtotime($last_comment->created_at) < \strtotime('- 3 days')) {
                \event(new TicketWentStale($last_comment->ticket));
            }
        }
    }
}
