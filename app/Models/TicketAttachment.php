<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use Auditable;
    use HasFactory;

    protected $appends = [
        'full_disk_path',
    ];

    protected function fullDiskPath(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->disk_path . '' . $this->file_name,
        );
    }

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs To A Ticket.
     */
    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
