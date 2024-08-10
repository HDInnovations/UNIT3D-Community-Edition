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

use App\Events\TicketWentStale;
use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\AntiXSS;

/**
 * App\Models\Comment.
 *
 * @property int                             $id
 * @property string                          $content
 * @property int                             $anon
 * @property int|null                        $user_id
 * @property int|null                        $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string                          $commentable_type
 * @property int                             $commentable_id
 */
class Comment extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array{anon: 'bool'}
     */
    protected function casts(): array
    {
        return [
            'anon' => 'bool',
        ];
    }

    /**
     * Belongs To A User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo<Model, $this>
     */
    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<self, $this>
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->oldest();
    }

    public function isParent(): bool
    {
        return null === $this->parent_id;
    }

    public function isChild(): bool
    {
        return null !== $this->parent_id;
    }

    /**
     * @param Builder<Comment> $builder
     */
    public function scopeParent(Builder $builder): void
    {
        $builder->whereNull('parent_id');
    }

    /**
     * Set The Articles Content After Its Been Purified.
     */
    public function setContentAttribute(?string $value): void
    {
        $this->attributes['content'] = $value === null ? null : htmlspecialchars((new AntiXSS())->xss_clean($value), ENT_NOQUOTES);
    }

    /**
     * Parse Content And Return Valid HTML.
     */
    public function getContentHtml(): string
    {
        $bbcode = new Bbcode();

        return (new Linkify())->linky($bbcode->parse($this->content));
    }

    /**
     * Nootify Staff There Is Stale Tickets.
     */
    public static function checkForStale(Ticket $ticket): void
    {
        if (empty($ticket->reminded_at) || strtotime((string) $ticket->reminded_at) < strtotime('+ 3 days')) {
            $last_comment = $ticket->comments()->latest('id')->first();

            if ($last_comment !== null && property_exists($last_comment, 'id') && $last_comment->id !== null && !$last_comment->user->group->is_modo && strtotime((string) $last_comment->created_at) < strtotime('- 3 days')) {
                event(new TicketWentStale($ticket));
            }
        }
    }
}
