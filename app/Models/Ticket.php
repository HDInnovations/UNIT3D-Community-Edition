<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use Auditable;
    use HasFactory;

    protected $casts = [
        'closed_at'   => 'datetime',
        'reminded_at' => 'datetime',
    ];

    protected $guarded = [];

    /**
     * @param  Builder<Ticket> $query
     * @return Builder<Ticket>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        if ($status === 'closed') {
            return $query->whereNotNull('closed_at');
        }

        if ($status === 'open') {
            return $query->whereNull('closed_at');
        }

        return $query;
    }

    /**
     * @param  Builder<Ticket> $query
     * @return Builder<Ticket>
     */
    public function scopeStale(Builder $query): Builder
    {
        return $query->with(['comments' => function ($query): void {
            $query->latest('id');
        }, 'comments.user'])
            ->has('comments')
            ->where('reminded_at', '<', strtotime('+ 3 days'))
            ->orWhereNull('reminded_at');
    }

    public static function checkForStaleTickets(): void
    {
        $open_tickets = self::status('open')
            ->whereNotNull('staff_id')
            ->get();

        foreach ($open_tickets as $open_ticket) {
            Comment::checkForStale($open_ticket);
        }
    }

    /**
     * Belongs To A User (Created).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Staff User (Assigned).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function staff(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Belongs To A Ticket Priority.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TicketPriority, self>
     */
    public function priority(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TicketPriority::class);
    }

    /**
     * Belongs To A Ticket Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TicketCategory, self>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * Has Many Ticket Attachments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TicketAttachment>
     */
    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany<Comment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
