<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    /**
     * Belongs To A User.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }

    /**
     * Belongs To A Uploader.
     */
    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Not needed yet but may use this soon.

        return $this->belongsTo(User::class, 'staff_id', 'id')->withDefault([
            'username' => 'System',
            'id'       => '1',
        ]);
    }
}
