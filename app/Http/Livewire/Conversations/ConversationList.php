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
use Illuminate\Support\Collection;
use Livewire\Component;

class ConversationList extends Component
{
    public $conversations;

    final public function mount(Collection $conversations): void
    {
        $this->conversations = $conversations;
    }

    final public function getListeners(): array
    {
        return [
            'echo-private:users.'.auth()->id().',Conversations\\ConversationCreated' => 'prependConversationFromBroadcast',
            'echo-private:users.'.auth()->id().',Conversations\\ConversationUpdated' => 'updateConversationFromBroadcast',
        ];
    }

    final public function prependConversation($id): void
    {
        $this->conversations->prepend(Conversation::find($id));
    }

    final public function updateConversationFromBroadcast($payload): void
    {
        $this->conversations->find($payload['conversation']['id'])->fresh();
    }

    final public function prependConversationFromBroadcast($payload): void
    {
        $this->prependConversation($payload['conversation']['id']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.conversations.conversation-list');
    }
}
