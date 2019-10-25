<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Events;

use App\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message details.
     *
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  Message  $message
     */
    public function __construct(Message $message)
    {
        $this->message = Message::find($message->id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PresenceChannel
     */
    public function broadcastOn()
    {
        // $this->dontBroadcastToCurrentUser();

        return new PresenceChannel('chatroom.'.$this->message->chatroom_id);
    }

    public function broadcastAs()
    {
        return 'delete.message';
    }
}
