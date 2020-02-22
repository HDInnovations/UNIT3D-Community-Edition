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

use App\Events\Chatter;
use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\Ping;
use App\Http\Resources\ChatMessageResource;
use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use App\Models\Message;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use Illuminate\Support\Str;

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

    /**
     * @var Bot
     */
    private $bot;

    /**
     * @var Echo
     */
    private $echo;

    /**
     * @var Audible
     */
    private $audible;

    public function __construct(Message $message, Chatroom $room, ChatStatus $status, User $user, Bot $bot, UserEcho $echo, UserAudible $audible)
    {
        $this->message = $message;
        $this->room = $room;
        $this->echo = $echo;
        $this->status = $status;
        $this->user = $user;
        $this->bot = $bot;
        $this->audible = $audible;
    }

    public function config()
    {
        return config('chat');
    }

    public function bots()
    {
        return $this->bot->all();
    }

    public function echoes($user_id)
    {
        return $this->echo->with([
            'bot',
            'user',
            'target',
            'room',
        ])->where(function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })
            ->orderBy('id', 'asc')
            ->get();
    }

    public function audibles($user_id)
    {
        return $this->audible->with([
            'bot',
            'user',
            'target',
            'room',
        ])->where(function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })
            ->latest()
            ->get();
    }

    public function rooms()
    {
        return $this->room->all();
    }

    public function roomFindOrFail($id)
    {
        return $this->room->findOrFail($id);
    }

    public function ping($type, $id)
    {
        if ($type == 'room') {
            $rooms = Chatroom::where('id', '>', 0)->get();
            foreach ($rooms as $room) {
                broadcast(new Ping($room->id, $id));
            }
        }

        return true;
    }

    public function message($user_id, $room_id, $message, $receiver = null, $bot = null)
    {
        if ($this->user->find($user_id)->censor) {
            $message = $this->censorMessage($message);
        }

        $message = $this->htmlifyMessage($message);

        $message = $this->message->create([
            'user_id'     => $user_id,
            'chatroom_id' => $room_id,
            'message'     => $message,
            'receiver_id' => $receiver,
            'bot_id'      => $bot,
        ]);

        $this->checkMessageLimits($room_id);

        broadcast(new MessageSent($message));

        return $message;
    }

    public function botMessage($bot_id, $room_id, $message, $receiver = null)
    {
        $user = $this->user->find($receiver);
        if ($user->censor) {
            $message = $this->censorMessage($message);
        }
        $message = $this->htmlifyMessage($message);
        $save = $this->message->create([
            'bot_id'      => $bot_id,
            'user_id'     => 1,
            'chatroom_id' => 0,
            'message'     => $message,
            'receiver_id' => $receiver,
        ]);

        $message = Message::with([
            'bot',
            'user.group',
            'user.chatStatus',
            'receiver.group',
            'receiver.chatStatus',
        ])->find($save->id);

        event(new Chatter('new.bot', $receiver, new ChatMessageResource($message)));
        event(new Chatter('new.ping', $receiver, ['type' => 'bot', 'id' => $bot_id]));
        $message->delete();
    }

    public function privateMessage($user_id, $room_id, $message, $receiver = null, $bot = null, $ignore = null)
    {
        if ($this->user->find($user_id)->censor) {
            $message = $this->censorMessage($message);
        }
        $message = $this->htmlifyMessage($message);

        $save = $this->message->create([
            'user_id'     => $user_id,
            'chatroom_id' => 0,
            'message'     => $message,
            'receiver_id' => $receiver,
            'bot_id'      => $bot,
        ]);

        $message = Message::with([
            'bot',
            'user.group',
            'user.chatStatus',
            'receiver.group',
            'receiver.chatStatus',
        ])->find($save->id);

        if ($ignore != null) {
            event(new Chatter('new.message', $user_id, new ChatMessageResource($message)));
        }
        event(new Chatter('new.message', $receiver, new ChatMessageResource($message)));

        if ($receiver != 1) {
            event(new Chatter('new.ping', $receiver, ['type' => 'target', 'id' => $user_id]));
        }

        return $message;
    }

    public function deleteMessage($id)
    {
        $message = $this->message->find($id);

        if ($message) {
            broadcast(new MessageDeleted($message));

            return $message->delete();
        }
    }

    public function messages($room_id)
    {
        return $this->message->with([
            'bot',
            'user.group',
            'chatroom',
            'user.chatStatus',
            'receiver.group',
            'receiver.chatStatus',
        ])->where(function ($query) use ($room_id) {
            $query->where('chatroom_id', '=', $room_id);
        })
            ->orderBy('id', 'desc')
            ->limit(config('chat.message_limit'))
            ->get();
    }

    public function botMessages($sender_id, $bot_id)
    {
        $systemUserId = User::where('username', 'System')->firstOrFail()->id;

        return $this->message->with([
            'bot',
            'user.group',
            'chatroom',
            'user.chatStatus',
            'receiver.group',
            'receiver.chatStatus',
        ])->where(function ($query) use ($sender_id, $bot_id, $systemUserId) {
            $query->whereRaw('(user_id = ? and receiver_id = ?)', [$sender_id, $systemUserId])->orWhereRaw('(user_id = ? and receiver_id = ?)', [$systemUserId, $sender_id]);
        })->where('bot_id', '=', $bot_id)
            ->orderBy('id', 'desc')
            ->limit(config('chat.message_limit'))
            ->get();
    }

    public function privateMessages($sender_id, $target_id)
    {
        return $this->message->with([
            'bot',
            'user.group',
            'chatroom',
            'user.chatStatus',
            'receiver.group',
            'receiver.chatStatus',
        ])->where(function ($query) use ($sender_id, $target_id) {
            $query->whereRaw('(user_id = ? and receiver_id = ?)', [$sender_id, $target_id])->orWhereRaw('(user_id = ? and receiver_id = ?)', [$target_id, $sender_id]);
        })
            ->orderBy('id', 'desc')
            ->limit(config('chat.message_limit'))
            ->get();
    }

    public function checkMessageLimits($room_id)
    {
        $messages = $this->messages($room_id)->toArray();
        $limit = config('chat.message_limit');
        $count = is_countable($messages) ? count($messages) : 0;

        // Lets purge all old messages and keep the database to the limit settings
        if ($count > $limit) {
            for ($x = 1; $x <= $count - $limit; $x++) {
                $message = array_pop($messages);
                echo $message['id']."\n";

                $message = $this->message->find($message['id']);

                if ($message->receiver_id === null) {
                    $message->delete();
                }
            }
        }
    }

    public function systemMessage($message, $bot = null)
    {
        $systemUserId = User::where('username', 'System')->first()->id;

        if ($bot) {
            $this->message($systemUserId, $this->systemChatroom(), $message, null, $bot);
        } else {
            $systemBotId = Bot::where('slug', 'systembot')->first()->id;

            $this->message($systemUserId, $this->systemChatroom(), $message, null, $systemBotId);
        }

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
            $status = $this->status->where('user_id', '=', $user->id)->first();
        }

        if (is_int($user)) {
            $status = $this->status->where('user_id', '=', $user)->first();
        }

        return $status;
    }

    public function statusFindOrFail($id)
    {
        return $this->status->findOrFail($id);
    }

    /**
     * @param $message
     *
     * @return string
     */
    protected function censorMessage($message)
    {
        foreach (config('censor.redact') as $word) {
            if (preg_match(sprintf('/\b%s(?=[.,]|$|\s)/mi', $word), $message)) {
                $message = str_replace($word, sprintf('<span class=\'censor\'>%s</span>', $word), $message);
            }
        }

        foreach (config('censor.replace') as $word => $rword) {
            if (Str::contains($message, $word)) {
                $message = str_replace($word, $rword, $message);
            }
        }

        return $message;
    }

    protected function htmlifyMessage($message)
    {
        // Soon

        return $message;
    }
}
