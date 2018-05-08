<?php

namespace App\Repositories;

use App\Chatroom;
use App\ChatStatus;
use App\Events\MessageSent;
use App\Message;
use App\User;

class ChatRepository
{

    /**
     * @var Message
     */
    private $message;

    /**
     * @var Chatroom
     */
    private $room;

    /**
     * @var ChatStatus
     */
    private $status;


    public function __construct(Message $message, Chatroom $room, ChatStatus $status)
    {
        $this->message = $message;
        $this->room = $room;
        $this->status = $status;
    }

    public function rooms()
    {
        return $this->room->with(['messages.user.group', 'messages.user.chatStatus'])->get();
    }

    public function roomFindOrFail($id)
    {
        return $this->room->findOrFail($id);
    }

    public function message($user_id, $room_id, $message)
    {
        $message = $this->message->create([
            'user_id' => $user_id,
            'chatroom_id' => $room_id,
            'message' => $message
        ]);

        broadcast(new MessageSent($message));

        return $message;
    }

    public function systemMessage($message)
    {
        $this->message(1, $this->systemChatroom(), $message);

        return $this;
    }

    public function systemChatroom($room = null)
    {
        $config = config('chat.system_chatroom');

        if ($room !== null)
        {
            if ($room instanceof Chatroom) {
                $room = $room->id;
            } elseif (is_int($room)) {
                $room = $this->room->findOrFail($room)->id;
            } else {
                $room = $this->room->whereName($room)->first()->id;
            }
        } elseif (is_int($config)) {
            $room = $this->room->findOrFail($config)->id;
        } elseif ($config instanceof Chatroom) {
            $room = $config->id;
        } else {
            $room = $this->room->whereName($config)->first()->id;
        }

        return $room;
    }

    public function statuses()
    {
        return $this->status->all();
    }

    public function status($user)
    {
        if ($user instanceof User) {
            $status = $this->status->where('user_id', $user->id)->first();
        }

        if (is_int($user)) {
            $status = $this->status->where('user_id', $user)->first();
        }

        return $status;
    }

    public function statusFindOrFail($id)
    {
        return $this->status->findOrFail($id);
    }

}