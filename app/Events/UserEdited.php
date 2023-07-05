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

namespace App\Events;

use App\Models\Chatroom;
use App\Models\UserEcho;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEdited implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $userId,
        private UserEcho $echo,
        public ?int $chatStatusId = null,
        public ?string $image = null,
        public ?string $username = null,
        public ?int $groupId = null,
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        switch (true) {
            case  $this->echo->user_id !== null && $this->echo->target_id !== null: // Send to PM
                $ids = [$this->echo->user_id, $this->echo->target_id];
                asort($ids);

                return new PresenceChannel('messages.pm.'.implode('-', $ids));
            case $this->echo->room_id !== null: // Send to chatroom
                return new PresenceChannel('messages.room.'.$this->echo->room_id);
            default: // Use system chatroom if no parameters are isn't provided
                $id = Chatroom::query()
                    ->where('name', '=', config('chat.system_chatroom'))
                    ->orWhere('id', '=', config('chat.system_chatroom'))
                    ->sole()
                    ->id;

                return new PresenceChannel('messages.room.'.$id);
        }
    }
}
