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

use App\Events\MessageSent;
use App\Models\Chatroom;
use Livewire\Component;

class NewMessage extends Component
{
    public $room;

    public $body = '';

    final public function mount(Chatroom $room): void
    {
        $this->room = $room;
    }

    final public function store(): void
    {
        $message = $this->room->messages()->create([
            'body'    => $this->body,
            'user_id' => auth()->user()->id
        ]);

        $this->emit('message.sent', $message->id);

        broadcast(new MessageSent($this->room, $message))->toOthers();

        $this->body = '';
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.chat.new-message');
    }
}
