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

namespace App\Http\Livewire\Conversations;

use App\Models\Conversation;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Events\Conversations\ConversationCreated;

class ConversationCreate extends Component
{
    public $users = [];

    public $body = '';

    public function addUser($user)
    {
        $this->users[] = $user;
    }

    public function create()
    {
        $this->validate([
            'body' => 'required',
            'users' => 'required',
        ]);

        $conversation = Conversation::create([
            'uuid' => Str::uuid(),
            'last_message_at' => now(),
        ]);

        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $conversation->users()->sync($this->collectUserIds());

        broadcast(new ConversationCreated($conversation))->toOthers();

        return redirect()->route('conversations.show', $conversation);
    }

    public function collectUserIds()
    {
        return collect($this->users)->merge([auth()->user()])->pluck('id')->unique();
    }

    public function render()
    {
        return view('livewire.conversations.conversation-create');
    }
}
