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
use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateMessage implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     * @param array<string, ?int> $echo
     */
    public function __construct(public Message $message, public array $echo = [])
    {
        $this->message = $message->load([
            'user:id,username,group_id,image,chat_status_id' => [
                'group:id,name,icon,color,effect',
                'chatStatus:id,name,color',
            ]
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PresenceChannel
    {
        switch (true) {
            case isset($this->echo['room_id']): // Send to chatroom
                return new PresenceChannel('messages.room.'.$this->echo['room_id']);
            case isset($this->echo['target_id']): // Send to PM
                $ids = [$this->echo['user_id'], $this->echo['target_id']];
                asort($ids);

                return new PresenceChannel('messages.pm.'.implode('-', $ids));
            default:// Use system chatroom if echo isn't provided
                $id = Chatroom::query()
                    ->where('name', '=', config('chat.system_chatroom'))
                    ->orWhere('id', '=', config('chat.system_chatroom'))
                    ->sole()
                    ->id;

                return new PresenceChannel('message.room'.$id);
        }
    }
}
