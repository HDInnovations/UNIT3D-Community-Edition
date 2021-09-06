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

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'last_message_at',
        'uuid'
    ];
    
    protected $dates = [
        'last_message_at'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('read_at')
            ->withTimestamps()
            ->oldest();
    }

    public function others()
    {
        return $this->users()->where('user_id', '!=', auth()->id());
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class)
            ->latest();
    }
}
