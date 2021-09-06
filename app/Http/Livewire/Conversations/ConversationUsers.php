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

use App\Events\Conversations\ConversationUpdated;
use App\Events\Conversations\UserAdded;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class ConversationUsers extends Component
{
    public $users;
    public $conversation;
    public $conversationId;

    final public function mount(Collection $users, Conversation $conversation): void
    {
        $this->users = $users;
        $this->conversation = $conversation;
        $this->conversationId = $conversation->id;
    }

    final public function getListeners(): array
    {
        return [
            "echo-private:conversations.{$this->conversationId},Conversations\\UserAdded" => 'pushUserFromBroadcast',
        ];
    }

    final public function pushUserFromBroadcast($payload): void
    {
        $this->pushUser($payload['user']['id']);
    }

    final public function pushUser($id)
    {
        $this->users->push($user = User::find($id));

        return $user;
    }

    final public function addUser($user): void
    {
        $this->conversation->users()->syncWithoutDetaching($user['id']);

        $user = $this->pushUser($user['id']);

        broadcast(new UserAdded($this->conversation, $user))->toOthers();
        broadcast(new ConversationUpdated($this->conversation));
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.conversations.conversation-users');
    }
}
