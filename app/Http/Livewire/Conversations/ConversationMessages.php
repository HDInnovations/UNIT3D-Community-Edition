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
use App\Models\ConversationMessage;
use Illuminate\Support\Collection;
use Livewire\Component;

class ConversationMessages extends Component
{
    public $messages;
    public $conversationId;

    final public function mount(Conversation $conversation, Collection $messages): void
    {
        $this->conversationId = $conversation->id;
        $this->messages = $messages;
    }

    final public function getListeners(): array
    {
        return [
            'message.created'                                                                => 'prependMessage',
            "echo-private:conversations.{$this->conversationId},Conversations\\MessageAdded" => 'prependMessageFromBroadcast',
        ];
    }

    final public function prependMessage($id): void
    {
        $this->messages->prepend(ConversationMessage::find($id));
    }

    final public function prependMessageFromBroadcast($payload): void
    {
        $this->prependMessage($payload['message']['id']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.conversations.conversation-messages');
    }
}
