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

namespace App\Http\Controllers\API;

use App\Bots\CasinoBot;
use App\Bots\NerdBot;
use App\Bots\SystemBot;
use App\Events\Chatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotResource;
use App\Http\Resources\ChatMessageResource;
use App\Http\Resources\ChatRoomResource;
use App\Http\Resources\UserAudibleResource;
use App\Http\Resources\UserEchoResource;
use App\Models\Bot;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Repositories\ChatRepository;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * @var ChatRepository
     */
    private $chat;

    /**
     * @var AuthManager
     */
    private $auth;

    public function __construct(ChatRepository $chat, AuthManager $auth)
    {
        $this->chat = $chat;
        $this->auth = $auth;
    }

    /* STATUSES */
    public function statuses()
    {
        return response($this->chat->statuses());
    }

    /* ECHOES */
    public function echoes()
    {
        $user = User::with(['echoes'])->findOrFail($this->auth->user()->id);

        if (!$user->echoes || (is_countable($user->echoes->toArray()) ? count($user->echoes->toArray()) : 0) < 1) {
            $echoes = new UserEcho();
            $echoes->user_id = $this->auth->user()->id;
            $echoes->room_id = 1;
            $echoes->save();
        }

        return UserEchoResource::collection($this->chat->echoes($this->auth->user()->id));
    }

    /* AUDIBLES */
    public function audibles()
    {
        $user = User::with(['audibles'])->findOrFail($this->auth->user()->id);

        if (!$user->audibles || (is_countable($user->audibles->toArray()) ? count($user->audibles->toArray()) : 0) < 1) {
            $audibles = new UserAudible();
            $audibles->user_id = $this->auth->user()->id;
            $audibles->room_id = 1;
            $audibles->status = 1;
            $audibles->save();
        }

        return UserAudibleResource::collection($this->chat->audibles($this->auth->user()->id));
    }

    /* BOTS */
    public function bots()
    {
        return BotResource::collection($this->chat->bots());
    }

    /* ROOMS */
    public function rooms()
    {
        return ChatRoomResource::collection($this->chat->rooms());
    }

    public function config()
    {
        return response($this->chat->config());
    }

    /* MESSAGES */
    public function messages($room_id)
    {
        return ChatMessageResource::collection($this->chat->messages($room_id));
    }

    /* MESSAGES */
    public function privateMessages($target_id)
    {
        return ChatMessageResource::collection($this->chat->privateMessages($this->auth->user()->id, $target_id));
    }

    /* MESSAGES */
    public function botMessages($bot_id)
    {
        $bot = Bot::where('id', '=', $bot_id)->firstOrFail();
        if ($bot->is_systembot) {
            $runbot = new SystemBot($this->chat);
        } elseif ($bot->is_nerdbot) {
            $runbot = new NerdBot($this->chat);
        }
        $runbot->process('message', $this->auth->user(), '', 0);

        return ChatMessageResource::collection($this->chat->botMessages($this->auth->user()->id, $bot->id));
    }

    public function createMessage(Request $request)
    {
        $user = $this->auth->user();

        $user_id = $user->id;
        $receiver_id = $request->input('receiver_id');
        $room_id = $request->input('chatroom_id');
        $bot_id = $request->input('bot_id');
        $message = $request->input('message');
        $targeted = $request->input('targeted');
        $save = $request->get('save');

        if ($user->can_chat === 0) {
            return response('error', 401);
        }

        // Temp Fix For HTMLPurifier
        if ($message === '<') {
            return response('error', 401);
        }

        $bot_dirty = 0;
        $bots = cache()->get('bots');
        if (!$bots || !is_array($bots) || count($bots) < 1) {
            $bots = Bot::where('active', '=', 1)->orderBy('position', 'asc')->get();
            $bot_dirty = 1;
        }

        if ($bot_dirty == 1) {
            $expiresAt = Carbon::now()->addMinutes(60);
            cache()->put('bots', $bots, $expiresAt);
        }

        $which = null;
        $target = null;
        $runbot = null;
        $trip = 'msg';
        if ($message && substr($message, 0, 1 + (strlen($trip))) === '/'.$trip) {
            $which = 'skip';
            $command = @explode(' ', $message);
            if (array_key_exists(1, $command)) {
                $receiver = User::where('username', 'like', $command[1])->firstOrFail();
                $receiver_id = $receiver->id;
                $clone = $command;
                array_shift($clone);
                array_shift($clone);
                $message = trim(implode(' ', $clone));
            }
            $bot_id = 1;
        }

        $trip = 'gift';
        if ($message && substr($message, 0, 1 + (strlen($trip))) === '/'.$trip) {
            $which = 'echo';
            $target = 'system';
            $message = '/bot gift'.substr($message, strlen($trip) + 1, strlen($message));
        }
        if ($target == 'system') {
            $runbot = new SystemBot($this->chat);
        }
        if ($which == null) {
            foreach ($bots as $bot) {
                if ($message && substr($message, 0, 1 + (strlen($bot->command))) === '/'.$bot->command) {
                    $which = 'echo';
                } elseif ($message && substr($message, 0, 1 + (strlen($bot->command))) === '!'.$bot->command) {
                    $which = 'public';
                } elseif ($message && substr($message, 0, 1 + (strlen($bot->command))) === '@'.$bot->command) {
                    $message = substr($message, 1 + strlen($bot->command), strlen($message));
                    $which = 'private';
                } elseif ($message && $receiver_id == 1 && $bot->id == $bot_id) {
                    if ($message && substr($message, 0, 1 + (strlen($bot->command))) === '/'.$bot->command) {
                        $message = substr($message, 1 + strlen($bot->command), strlen($message));
                    }
                    if ($message && substr($message, 0, 1 + (strlen($bot->command))) === '!'.$bot->command) {
                        $message = substr($message, 1 + strlen($bot->command), strlen($message));
                    }
                    if ($message && substr($message, 0, 1 + (strlen($bot->command))) === '@'.$bot->command) {
                        $message = substr($message, 1 + strlen($bot->command), strlen($message));
                    }
                    $which = 'message';
                }
                if ($which != null) {
                    break;
                }
            }
        }

        if ($which != null && $which != 'skip' && !$runbot) {
            if ($bot->is_systembot) {
                $runbot = new SystemBot($this->chat);
            } elseif ($bot->is_nerdbot) {
                $runbot = new NerdBot($this->chat);
            } elseif ($bot->is_casinobot) {
                $runbot = new CasinoBot($this->chat);
            }
        }
        if ($runbot) {
            return $runbot->process($which, $this->auth->user(), $message, 0);
        }

        $echo = false;
        if ($receiver_id && $receiver_id > 0) {
            $sender_dirty = 0;
            $receiver_dirty = 0;
            $sender_echoes = cache()->get('user-echoes'.$user_id);
            $receiver_echoes = cache()->get('user-echoes'.$receiver_id);
            if (!$sender_echoes || !is_array($sender_echoes) || count($sender_echoes) < 1) {
                $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
            }
            if (!$receiver_echoes || !is_array($receiver_echoes) || count($receiver_echoes) < 1) {
                $receiver_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
            }
            $sender_listening = false;
            foreach ($sender_echoes as $se => $sender_echo) {
                if ($sender_echo['target_id'] == $receiver_id) {
                    $sender_listening = true;
                }
            }
            if (!$sender_listening) {
                $sender_port = new UserEcho();
                $sender_port->user_id = $user_id;
                $sender_port->target_id = $receiver_id;
                $sender_port->save();
                $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
                $sender_dirty = 1;
            }
            $receiver_listening = false;
            foreach ($receiver_echoes as $se => $receiver_echo) {
                if ($receiver_echo['target_id'] == $user_id) {
                    $receiver_listening = true;
                }
            }
            if (!$receiver_listening) {
                $receiver_port = new UserEcho();
                $receiver_port->user_id = $receiver_id;
                $receiver_port->target_id = $user_id;
                $receiver_port->save();
                $receiver_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
                $receiver_dirty = 1;
            }
            if ($sender_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
                event(new Chatter('echo', $user_id, UserEchoResource::collection($sender_echoes)));
            }
            if ($receiver_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$receiver_id, $receiver_echoes, $expiresAt);
                event(new Chatter('echo', $receiver_id, UserEchoResource::collection($receiver_echoes)));
            }

            $sender_dirty = 0;
            $receiver_dirty = 0;
            $sender_audibles = cache()->get('user-audibles'.$user_id);
            $receiver_audibles = cache()->get('user-audibles'.$receiver_id);
            if (!$sender_audibles || !is_array($sender_audibles) || count($sender_audibles) < 1) {
                $sender_audibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
            }
            if (!$receiver_audibles || !is_array($receiver_audibles) || count($receiver_audibles) < 1) {
                $receiver_audibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
            }
            $sender_listening = false;
            foreach ($sender_audibles as $se => $sender_echo) {
                if ($sender_echo['target_id'] == $receiver_id) {
                    $sender_listening = true;
                }
            }
            if (!$sender_listening) {
                $sender_port = new UserAudible();
                $sender_port->user_id = $user_id;
                $sender_port->target_id = $receiver_id;
                $sender_port->status = 0;
                $sender_port->save();
                $sender_audibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
                $sender_dirty = 1;
            }
            $receiver_listening = false;
            foreach ($receiver_audibles as $se => $receiver_echo) {
                if ($receiver_echo['target_id'] == $user_id) {
                    $receiver_listening = true;
                }
            }
            if (!$receiver_listening) {
                $receiver_port = new UserAudible();
                $receiver_port->user_id = $receiver_id;
                $receiver_port->target_id = $user_id;
                $receiver_port->status = 0;
                $receiver_port->save();
                $receiver_audibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
                $receiver_dirty = 1;
            }
            if ($sender_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
                event(new Chatter('audible', $user_id, UserAudibleResource::collection($sender_audibles)));
            }
            if ($receiver_dirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$receiver_id, $receiver_audibles, $expiresAt);
                event(new Chatter('audible', $receiver_id, UserAudibleResource::collection($receiver_audibles)));
            }

            $room_id = 0;
            $ignore = $bot_id > 0 && $receiver_id == 1 ? true : null;
            $save = true;
            $echo = true;
            $message = $this->chat->privateMessage($user_id, $room_id, $message, $receiver_id, null, $ignore);
        } else {
            $receiver_id = null;
            $bot_id = null;
            $message = $this->chat->message($user_id, $room_id, $message, $receiver_id, $bot_id);
        }

        if (!$save) {
            $message->delete();
        }

        if ($save && $echo !== false) {
            return new ChatMessageResource($message);
        }

        return response('success');
    }

    public function deleteMessage($id)
    {
        $this->chat->deleteMessage($id);

        return response('success');
    }

    public function deleteRoomEcho(Request $request, $user_id)
    {
        $echo = UserEcho::where('user_id', '=', $user_id)->where('room_id', '=', $request->input('room_id'))->firstOrFail();
        $echo->delete();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $room = $this->chat->roomFindOrFail($request->input('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        event(new Chatter('echo', $user_id, UserEchoResource::collection($sender_echoes)));

        return response($user);
    }

    public function deleteTargetEcho(Request $request, $user_id)
    {
        $echo = UserEcho::where('user_id', '=', $user_id)->where('target_id', '=', $request->input('target_id'))->firstOrFail();
        $echo->delete();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        event(new Chatter('echo', $user_id, UserEchoResource::collection($sender_echoes)));

        return response($user);
    }

    public function deleteBotEcho(Request $request, $user_id)
    {
        $echo = UserEcho::where('user_id', '=', $user_id)->where('bot_id', '=', $request->input('bot_id'))->firstOrFail();
        $echo->delete();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        event(new Chatter('echo', $user_id, UserEchoResource::collection($sender_echoes)));

        return response($user);
    }

    public function toggleRoomAudible(Request $request, $user_id)
    {
        $echo = UserAudible::where('user_id', '=', $user_id)->where('room_id', '=', $request->input('room_id'))->firstOrFail();
        $echo->status = ($echo->status == 1 ? 0 : 1);
        $echo->save();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        event(new Chatter('audible', $user_id, UserAudibleResource::collection($sender_audibles)));

        return response($user);
    }

    public function toggleTargetAudible(Request $request, $user_id)
    {
        $echo = UserAudible::where('user_id', '=', $user_id)->where('target_id', '=', $request->input('target_id'))->firstOrFail();
        $echo->status = ($echo->status == 1 ? 0 : 1);
        $echo->save();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = UserAudible::with(['target', 'room', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        event(new Chatter('audible', $user_id, UserAudibleResource::collection($sender_audibles)));

        return response($user);
    }

    public function toggleBotAudible(Request $request, $user_id)
    {
        $echo = UserAudible::where('user_id', '=', $user_id)->where('bot_id', '=', $request->input('bot_id'))->firstOrFail();
        $echo->status = ($echo->status == 1 ? 0 : 1);
        $echo->save();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = UserAudible::with(['bot', 'room', 'bot'])->where('user_id', $user_id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        event(new Chatter('audible', $user_id, UserAudibleResource::collection($sender_audibles)));

        return response($user);
    }

    /* USERS */
    public function updateUserChatStatus(Request $request, $id)
    {
        $systemUser = User::where('username', 'System')->firstOrFail();

        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);
        $status = $this->chat->statusFindOrFail($request->input('status_id'));

        $log = '[url=/users/'.$user->username.']'.$user->username.'[/url] has updated their status to [b]'.$status->name.'[/b]';

        $message = $this->chat->message($systemUser->id, $user->chatroom->id, $log, null);
        $message->save();

        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);
        $user->save();

        return response($user);
    }

    public function updateUserRoom(Request $request, $id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);
        $room = $this->chat->roomFindOrFail($request->input('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        $sender_dirty = 0;
        $sender_echoes = cache()->get('user-echoes'.$id);
        if (!$sender_echoes || !is_array($sender_echoes) || count($sender_echoes) < 1) {
            $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$id])->get();
        }
        $sender_listening = false;
        foreach ($sender_echoes as $se => $sender_echo) {
            if ($sender_echo['room_id'] == $room->id) {
                $sender_listening = true;
            }
        }
        if (!$sender_listening) {
            $sender_port = new UserEcho();
            $sender_port->user_id = $id;
            $sender_port->room_id = $room->id;
            $sender_port->save();
            $sender_echoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$id])->get();
            $sender_dirty = 1;
        }
        if ($sender_dirty == 1) {
            $expiresAt = Carbon::now()->addMinutes(60);
            cache()->put('user-echoes'.$id, $sender_echoes, $expiresAt);
            event(new Chatter('echo', $id, UserEchoResource::collection($sender_echoes)));
        }

        return response($user);
    }

    public function updateUserTarget($id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);

        return response($user);
    }

    public function updateBotTarget($id)
    {
        $user = User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);

        return response($user);
    }
}
