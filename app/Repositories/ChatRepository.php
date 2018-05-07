<?php

namespace App\Repositories;

use App\Chatroom;
use App\Events\MessageSent;
use App\Message;
use Illuminate\Database\Eloquent\Model;

class ChatRepository
{

    protected $broadcast = true;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var Chatroom
     */
    private $room;


    public function __construct(Message $message, Chatroom $room)
    {
        $this->message = $message;
        $this->room = $room;
    }

    public function message($user_id, $room_id, $message)
    {
        $message = $this->message->create([
            'user_id' => $user_id,
            'chatroom_id' => $room_id,
            'message' => $message
        ]);

        if ($this->broadcast)
            broadcast(new MessageSent($message));

        return $message;
    }

    /**
     * @param $message
     * @return mixed
     */
    public function system($message)
    {
        $this->message(1, $this->systemChatroom(), $message);

        return $this;
    }

    public function dontBroadcast()
    {
        $this->broadcast = false;

        return $this;
    }

    public function systemChatroom($room = null)
    {
        $config = config('chat.system_chatroom');

        if ($room !== null)
        {
            if ($room instanceof Model) {
                $room = $room->id;
            } elseif (is_int($room)) {
                $room = $this->room->findOrFail($room)->id;
            } else {
                $room = $this->room->whereName($room)->first()->id;
            }
        } elseif (is_int($config)) {
            $room = $this->room->findOrFail($config)->id;
        } elseif ($config instanceof Model) {
            $room = $config->id;
        } else {
            $room = $this->room->whereName($config)->first()->id;
        }

        return $room;
    }

}