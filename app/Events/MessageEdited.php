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
use App\Http\Resources\ChatMessageResource;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageEdited implements ShouldBroadcastNow
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
        $message = Message::with(['bot', 'user.group', 'user.chatStatus'])->find($message->id);
        $this->message = new ChatMessageResource($message);
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
        return 'edit.message';
    }
}
