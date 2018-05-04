<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{
    public function users()
    {
        return $this->hasMany(User::class, 'chat_status_id');
    }
}
