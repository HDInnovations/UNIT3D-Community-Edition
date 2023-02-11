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

namespace App\Http\Livewire\Chat;

use App\Models\Chatroom;
use App\Models\Message;
use Livewire\Component;

class Messages extends Component
{
    public $chatroomId;

    public $messages;

    final public function mount(Chatroom $chatroom, Message $messages): void
    {
        $this->chatroomId = $chatroom->id;
        $this->messages = $messages;
    }

    final public function getListeners(): array
    {
        return [
            'message.sent'                                            => 'prependMessage',
            "echo-private:chat.{$this->chatroomId},Chat\\MessageSent" => 'prependMessageFromBroadcast'
        ];
    }

    final public function prependMessage(int $id): void
    {
        $this->messages->prepend(Message::find($id));
    }

    final public function prependMessageFromBroadcast($payload): void
    {
        $this->prependMessage($payload['message']['id']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.chat.messages');
    }
}
