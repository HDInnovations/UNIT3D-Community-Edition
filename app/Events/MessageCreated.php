<?php

declare(strict_types=1);

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

namespace App\Events;

use App\Models\Chatroom;
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Message $message,
        private readonly ?int $senderId = null,
        private readonly ?int $roomId = null,
        private readonly ?int $receiverId = null,
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        switch (true) {
            case  $this->senderId !== null && $this->receiverId !== null: // Send to PM
                $ids = [$this->senderId, $this->receiverId];
                asort($ids);

                return new PresenceChannel('messages.pm.'.implode('-', $ids));
            case $this->roomId !== null: // Send to chatroom
                return new PresenceChannel('messages.room.'.$this->roomId);
            default: // Use system chatroom if no parameters are isn't provided
                $id = Chatroom::query()
                    ->where(\is_int(config('chat.system_chatroom')) ? 'id' : 'name', '=', config('chat.system_chatroom'))
                    ->soleValue('id');

                return new PresenceChannel('messages.room.'.$id);
        }
    }
}
