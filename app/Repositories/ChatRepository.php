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

namespace App\Repositories;

use App\Events\MessageCreated;
use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;

class ChatRepository
{
    public function systemMessage(?string $message): void
    {
        MessageCreated::dispatch(Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => Chatroom::query()
                ->where('name', '=', config('chat.system_chatroom'))
                ->orWhere('id', '=', config('chat.system_chatroom'))
                ->sole()
                ->id,
            'message'     => $message ?? '',
            'receiver_id' => null,
        ]));
    }
}
