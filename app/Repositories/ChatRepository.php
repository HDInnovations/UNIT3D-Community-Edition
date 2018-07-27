<?php

namespace App\Repositories;

use App\Chatroom;
use App\ChatStatus;
use App\Events\MessageDeleted;
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

    /**
     * @var User
     */
    private $user;


    public function __construct(Message $message, Chatroom $room, ChatStatus $status, User $user)
    {
        $this->message = $message;
        $this->room = $room;
        $this->status = $status;
        $this->user = $user;
    }

    public function config()
    {
        return config('chat');
    }

    public function rooms()
    {
        return $this->room->all();
    }

    public function roomFindOrFail($id)
    {
        return $this->room->findOrFail($id);
    }

    public function message($user_id, $room_id, $message)
    {
        if ($this->user->find($user_id)->censor) {
            $message = $this->censorMessage($message);
        }


        $message = $this->message->create([
            'user_id' => $user_id,
            'chatroom_id' => $room_id,
            'message' => $message
        ]);

        $this->checkMessageLimits($room_id);

        broadcast(new MessageSent($message));

        return $message;
    }

    public function deleteMessage($id)
    {
        $message = $this->message->find($id);

        broadcast(new MessageDeleted($message));

        return $message->delete();
    }

    public function messages($room_id)
    {
        return $this->message->with(['user.group', 'user.chatStatus'])
            ->where('chatroom_id', $room_id)
            ->latest()
            ->limit(config('chat.message_limit'))
            ->get();
    }

    public function checkMessageLimits($room_id)
    {
        $messages = $this->messages($room_id)->toArray();
        $limit = config('chat.message_limit');
        $count = count($messages);

        // Lets purge all old messages and keep the database to the limit settings
        if ($count > $limit) {
            for ($x = 1; $x <= $count - $limit; $x++) {
                $message = array_pop($messages);
                echo $message['id'] . "\n";

                $this->message->find($message['id'])->delete();
            }
        }
    }

    public function systemMessage($message)
    {
        $this->message(1, $this->systemChatroom(), $message);

        return $this;
    }

    public function systemChatroom($room = null)
    {
        $config = config('chat.system_chatroom');

        if ($room !== null) {
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

    /**
     * @param $message
     * @return string
     */
    protected function censorMessage($message)
    {
        foreach (config('censor.redact') as $word) {
            if (preg_match("/\b$word(?=[.,]|$|\s)/mi", $message)) {
                $message = str_replace($word, "<span class='censor'>{$word}</span>", $message);
            }
        }

        foreach (config('censor.replace') as $word => $rword) {
            if (str_contains($message, $word)) {
                $message = str_replace($word, $rword, $message);
            }
        }

        return $message;
    }
}
