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
use Livewire\Component;

class Users extends Component
{
    public $chatroomId;

    public $users;

    final public function mount(Chatroom $chatroom): void
    {
        $this->chatroomId = $chatroom->id;
    }

    final public function getListeners(): array
    {
        return [
            "echo-presence:chat.{$this->chatroomId},here"    => 'setUsersHere',
            "echo-presence:chat.{$this->chatroomId},joining" => 'setUserJoining',
            "echo-presence:chat.{$this->chatroomId},leaving" => 'setUserLeaving',
        ];
    }

   final public function setUsersHere($users): void
   {
       $this->users = $users;
   }

    final public function setUserJoining($user): void
    {
        $this->users[] = $user;
    }

    final public function setUserLeaving($user): bool
    {
        $this->users = array_filter($this->users, fn ($u) => $u['id'] !== $user['id']);
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.chat.users');
    }
}
