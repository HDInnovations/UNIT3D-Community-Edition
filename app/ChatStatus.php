<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{
    /**
     * A Status Has Many Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'chat_status_id');
    }
}
