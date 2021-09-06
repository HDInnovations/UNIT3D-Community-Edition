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
use Illuminate\Support\Collection;

class ConversationList extends Component
{
    public $conversations;
    
    public function mount(Collection $conversations)
    {
        $this->conversations = $conversations;
    }

    public function getListeners()
    {
        return [
            'echo-private:users.' . auth()->id() . ',Conversations\\ConversationCreated' => 'prependConversationFromBroadcast',
            'echo-private:users.' . auth()->id() . ',Conversations\\ConversationUpdated' => 'updateConversationFromBroadcast',
        ];
    }

    public function prependConversation($id)
    {
        $this->conversations->prepend(Conversation::find($id));
    }

    public function updateConversationFromBroadcast($payload)
    {
        $this->conversations->find($payload['conversation']['id'])->fresh();
    }

    public function prependConversationFromBroadcast($payload)
    {
        $this->prependConversation($payload['conversation']['id']);
    }

    public function render()
    {
        return view('livewire.conversations.conversation-list');
    }
}
