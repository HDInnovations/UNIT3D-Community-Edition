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
use App\Events\Conversations\MessageAdded;
use App\Events\Conversations\ConversationUpdated;

class ConversationReply extends Component
{
    public $conversation;
    
    public $body = '';

    public function mount(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function reply()
    {
        $this->validate([
            'body' => 'required'
        ]);
        
        $message = $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $this->body
        ]);

        $this->conversation->update([
            'last_message_at' => now()
        ]);

        foreach ($this->conversation->others as $user) {
            $user->conversations()->updateExistingPivot($this->conversation, [
                'read_at' => null
            ]);
        }

        broadcast(new MessageAdded($message))->toOthers();
        broadcast(new ConversationUpdated($message->conversation));

        $this->emit('message.created', $message->id);

        $this->body = '';
    }

    public function render()
    {
        return view('livewire.conversations.conversation-reply');
    }
}
