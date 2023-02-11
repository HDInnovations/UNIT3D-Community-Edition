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

namespace App\Traits;

use App\Events\MessageSent;
use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;

trait ChatMessage
{
    public function emitChatMessage(string $body): void
    {
        $user = User::findOrFail(config('chat.bot_account'))->id;

        $chatroom = Chatroom::findOrFail(config('chat.chatroom'))->id;

        $message = Message::create([
            'user_id'     => $user,
            'chatroom_id' => $chatroom,
            'message'     => $body,
        ]);

        broadcast(new MessageSent($chatroom, $message))->toOthers();
    }
}
