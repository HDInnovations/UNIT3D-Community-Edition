<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorrentBdinfo extends Model
{
    use HasFactory;

    /** The Attributes That Are Mass Assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bdinfo',
    ];

    /**
     * Belongs To A Torrent.
     */
    public function torrent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Torrent::class);
    }
}
