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

use App\Repositories\ChatRepository;
use Illuminate\Auth\AuthManager;

/**
 * @see \Tests\Feature\Http\Controllers\API\ChatControllerTest
 */
class ChatController extends \App\Http\Controllers\Controller
{
    /**
     * @var ChatRepository
     */
    private $chatRepository;
    /**
     * @var AuthManager
     */
    private $authManager;

    public function __construct(private \App\Repositories\ChatRepository $chatRepository, private \Illuminate\Contracts\Auth\Factory $authManager)
    {
        $this->chatRepository = $chatRepository;
        $this->authManager = $authFactory;
    }

    /* STATUSES */
    public function statuses()
    {
        return \response($this->chatRepository->statuses());
    }

    /* ECHOES */
    public function echoes()
    {
        $user = \App\Models\User::with(['echoes'])->findOrFail($this->authManager->user()->id);
        if (! $user->echoes || (\is_countable($user->echoes->toArray()) ? \count($user->echoes->toArray()) : 0) < 1) {
            $userEcho = new \App\Models\UserEcho();
            $userEcho->user_id = $this->authManager->user()->id;
            $userEcho->room_id = 1;
            $userEcho->save();
        }

        return \App\Http\Resources\UserEchoResource::collection($this->chatRepository->echoes($this->authManager->user()->id));
    }

    /* AUDIBLES */
    public function audibles()
    {
        $user = \App\Models\User::with(['audibles'])->findOrFail($this->authManager->user()->id);
        if (! $user->audibles || (\is_countable($user->audibles->toArray()) ? \count($user->audibles->toArray()) : 0) < 1) {
            $userAudible = new \App\Models\UserAudible();
            $userAudible->user_id = $this->authManager->user()->id;
            $userAudible->room_id = 1;
            $userAudible->status = 1;
            $userAudible->save();
        }

        return \App\Http\Resources\UserAudibleResource::collection($this->chatRepository->audibles($this->authManager->user()->id));
    }

    /* BOTS */
    public function bots()
    {
        return \App\Http\Resources\BotResource::collection($this->chatRepository->bots());
    }

    /* ROOMS */
    public function rooms()
    {
        return \App\Http\Resources\ChatRoomResource::collection($this->chatRepository->rooms());
    }

    public function config()
    {
        return \response($this->chatRepository->config());
    }

    /* MESSAGES */
    public function messages($room_id)
    {
        return \App\Http\Resources\ChatMessageResource::collection($this->chatRepository->messages($room_id));
    }

    /* MESSAGES */
    public function privateMessages($target_id)
    {
        return \App\Http\Resources\ChatMessageResource::collection($this->chatRepository->privateMessages($this->authManager->user()->id, $target_id));
    }

    /* MESSAGES */
    public function botMessages($bot_id)
    {
        $bot = \App\Models\Bot::where('id', '=', $bot_id)->firstOrFail();
        if ($bot->is_systembot) {
            $runbot = new \App\Bots\SystemBot($this->chatRepository);
        } elseif ($bot->is_nerdbot) {
            $runbot = new \App\Bots\NerdBot($this->chatRepository);
        }
        $runbot->process('message', $this->authManager->user(), '', 0);

        return \App\Http\Resources\ChatMessageResource::collection($this->chatRepository->botMessages($this->authManager->user()->id, $bot->id));
    }

    public function createMessage(\Illuminate\Http\Request $request)
    {
        $user = $this->authManager->user();
        $user_id = $user->id;
        $receiver_id = $request->input('receiver_id');
        $room_id = $request->input('chatroom_id');
        $bot_id = $request->input('bot_id');
        $message = $request->input('message');
        $targeted = $request->input('targeted');
        $save = $request->get('save');
        if ($user->can_chat === 0) {
            return \response('error', 401);
        }
        // Temp Fix For HTMLPurifier
        if ($message === '<') {
            return \response('error', 401);
        }
        $bot_dirty = 0;
        $bots = \cache()->get('bots');
        if (! $bots || ! \is_array($bots) || \count($bots) < 1) {
            $bots = \App\Models\Bot::where('active', '=', 1)->orderBy('position', 'asc')->get();
            $bot_dirty = 1;
        }
        if ($bot_dirty == 1) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
            \cache()->put('bots', $bots, $expiresAt);
        }
        $which = null;
        $target = null;
        $runbot = null;
        $trip = 'msg';
        if ($message && \substr($message, 0, 1 + \strlen($trip)) === '/'.$trip) {
            $which = 'skip';
            $command = @\explode(' ', $message);
            if (\array_key_exists(1, $command)) {
                $receiver = \App\Models\User::where('username', 'like', $command[1])->firstOrFail();
                $receiver_id = $receiver->id;
                $clone = $command;
                \array_shift($clone);
                \array_shift($clone);
                $message = \trim(\implode(' ', $clone));
            }
            $bot_id = 1;
        }
        $trip = 'gift';
        if ($message && \substr($message, 0, 1 + \strlen($trip)) === '/'.$trip) {
            $which = 'echo';
            $target = 'system';
            $message = '/bot gift'.\substr($message, \strlen($trip) + 1, \strlen($message));
        }
        if ($target == 'system') {
            $runbot = new \App\Bots\SystemBot($this->chatRepository);
        }
        if ($which == null) {
            foreach ($bots as $bot) {
                if ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '/'.$bot->command) {
                    $which = 'echo';
                } elseif ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '!'.$bot->command) {
                    $which = 'public';
                } elseif ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '@'.$bot->command) {
                    $message = \substr($message, 1 + \strlen($bot->command), \strlen($message));
                    $which = 'private';
                } elseif ($message && $receiver_id == 1 && $bot->id == $bot_id) {
                    if ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '/'.$bot->command) {
                        $message = \substr($message, 1 + \strlen($bot->command), \strlen($message));
                    }
                    if ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '!'.$bot->command) {
                        $message = \substr($message, 1 + \strlen($bot->command), \strlen($message));
                    }
                    if ($message && \substr($message, 0, 1 + \strlen($bot->command)) === '@'.$bot->command) {
                        $message = \substr($message, 1 + \strlen($bot->command), \strlen($message));
                    }
                    $which = 'message';
                }
                if ($which != null) {
                    break;
                }
            }
        }
        if ($which != null && $which != 'skip' && ! $runbot) {
            if ($bot->is_systembot) {
                $runbot = new \App\Bots\SystemBot($this->chatRepository);
            } elseif ($bot->is_nerdbot) {
                $runbot = new \App\Bots\NerdBot($this->chatRepository);
            } elseif ($bot->is_casinobot) {
                $runbot = new \App\Bots\CasinoBot($this->chatRepository);
            }
        }
        if ($runbot !== null) {
            return $runbot->process($which, $this->authManager->user(), $message, 0);
        }
        $echo = false;
        if ($receiver_id && $receiver_id > 0) {
            $sender_dirty = 0;
            $receiver_dirty = 0;
            $sender_echoes = \cache()->get('user-echoes'.$user_id);
            $receiver_echoes = \cache()->get('user-echoes'.$receiver_id);
            if (! $sender_echoes || ! \is_array($sender_echoes) || (\is_countable($sender_echoes) ? \count($sender_echoes) : 0) < 1) {
                $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
            }
            if (! $receiver_echoes || ! \is_array($receiver_echoes) || (\is_countable($receiver_echoes) ? \count($receiver_echoes) : 0) < 1) {
                $receiver_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
            }
            $sender_listening = false;
            foreach ($sender_echoes as $se => $sender_echo) {
                if ($sender_echo['target_id'] == $receiver_id) {
                    $sender_listening = true;
                }
            }
            if (! $sender_listening) {
                $sender_port = new \App\Models\UserEcho();
                $sender_port->user_id = $user_id;
                $sender_port->target_id = $receiver_id;
                $sender_port->save();
                $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
                $sender_dirty = 1;
            }
            $receiver_listening = false;
            foreach ($receiver_echoes as $se => $receiver_echo) {
                if ($receiver_echo['target_id'] == $user_id) {
                    $receiver_listening = true;
                }
            }
            if (! $receiver_listening) {
                $receiver_port = new \App\Models\UserEcho();
                $receiver_port->user_id = $receiver_id;
                $receiver_port->target_id = $user_id;
                $receiver_port->save();
                $receiver_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
                $receiver_dirty = 1;
            }
            if ($sender_dirty == 1) {
                $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
                \cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
                \event(new \App\Events\Chatter('echo', $user_id, \App\Http\Resources\UserEchoResource::collection($sender_echoes)));
            }
            if ($receiver_dirty == 1) {
                $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
                \cache()->put('user-echoes'.$receiver_id, $receiver_echoes, $expiresAt);
                \event(new \App\Events\Chatter('echo', $receiver_id, \App\Http\Resources\UserEchoResource::collection($receiver_echoes)));
            }
            $sender_dirty = 0;
            $receiver_dirty = 0;
            $sender_audibles = \cache()->get('user-audibles'.$user_id);
            $receiver_audibles = \cache()->get('user-audibles'.$receiver_id);
            if (! $sender_audibles || ! \is_array($sender_audibles) || (\is_countable($sender_audibles) ? \count($sender_audibles) : 0) < 1) {
                $sender_audibles = \App\Models\UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
            }
            if (! $receiver_audibles || ! \is_array($receiver_audibles) || (\is_countable($receiver_audibles) ? \count($receiver_audibles) : 0) < 1) {
                $receiver_audibles = \App\Models\UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
            }
            $sender_listening = false;
            foreach ($sender_audibles as $se => $sender_echo) {
                if ($sender_echo['target_id'] == $receiver_id) {
                    $sender_listening = true;
                }
            }
            if (! $sender_listening) {
                $sender_port = new \App\Models\UserAudible();
                $sender_port->user_id = $user_id;
                $sender_port->target_id = $receiver_id;
                $sender_port->status = 0;
                $sender_port->save();
                $sender_audibles = \App\Models\UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
                $sender_dirty = 1;
            }
            $receiver_listening = false;
            foreach ($receiver_audibles as $se => $receiver_echo) {
                if ($receiver_echo['target_id'] == $user_id) {
                    $receiver_listening = true;
                }
            }
            if (! $receiver_listening) {
                $receiver_port = new \App\Models\UserAudible();
                $receiver_port->user_id = $receiver_id;
                $receiver_port->target_id = $user_id;
                $receiver_port->status = 0;
                $receiver_port->save();
                $receiver_audibles = \App\Models\UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiver_id])->get();
                $receiver_dirty = 1;
            }
            if ($sender_dirty == 1) {
                $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
                \cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
                \event(new \App\Events\Chatter('audible', $user_id, \App\Http\Resources\UserAudibleResource::collection($sender_audibles)));
            }
            if ($receiver_dirty == 1) {
                $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
                \cache()->put('user-audibles'.$receiver_id, $receiver_audibles, $expiresAt);
                \event(new \App\Events\Chatter('audible', $receiver_id, \App\Http\Resources\UserAudibleResource::collection($receiver_audibles)));
            }
            $room_id = 0;
            $ignore = $bot_id > 0 && $receiver_id == 1 ? true : null;
            $save = true;
            $echo = true;
            $message = $this->chatRepository->privateMessage($user_id, $room_id, $message, $receiver_id, null, $ignore);
        } else {
            $receiver_id = null;
            $bot_id = null;
            $message = $this->chatRepository->message($user_id, $room_id, $message, $receiver_id, $bot_id);
        }
        if (! $save) {
            $message->delete();
        }
        if ($save && $echo) {
            return new \App\Http\Resources\ChatMessageResource($message);
        }

        return \response('success');
    }

    public function deleteMessage($id)
    {
        $this->chatRepository->deleteMessage($id);

        return \response('success');
    }

    public function deleteRoomEcho(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserEcho::where('user_id', '=', $user_id)->where('room_id', '=', $request->input('room_id'))->firstOrFail();
        $echo->delete();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $room = $this->chatRepository->roomFindOrFail($request->input('room_id'));
        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);
        $user->save();
        $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        \event(new \App\Events\Chatter('echo', $user_id, \App\Http\Resources\UserEchoResource::collection($sender_echoes)));

        return \response($user);
    }

    public function deleteTargetEcho(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserEcho::where('user_id', '=', $user_id)->where('target_id', '=', $request->input('target_id'))->firstOrFail();
        $echo->delete();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        \event(new \App\Events\Chatter('echo', $user_id, \App\Http\Resources\UserEchoResource::collection($sender_echoes)));

        return \response($user);
    }

    public function deleteBotEcho(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserEcho::where('user_id', '=', $user_id)->where('bot_id', '=', $request->input('bot_id'))->firstOrFail();
        $echo->delete();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($user_id);
        $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-echoes'.$user_id, $sender_echoes, $expiresAt);
        \event(new \App\Events\Chatter('echo', $user_id, \App\Http\Resources\UserEchoResource::collection($sender_echoes)));

        return \response($user);
    }

    public function toggleRoomAudible(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserAudible::where('user_id', '=', $user_id)->where('room_id', '=', $request->input('room_id'))->firstOrFail();
        $echo->status = $echo->status == 1 ? 0 : 1;
        $echo->save();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = \App\Models\UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        \event(new \App\Events\Chatter('audible', $user_id, \App\Http\Resources\UserAudibleResource::collection($sender_audibles)));

        return \response($user);
    }

    public function toggleTargetAudible(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserAudible::where('user_id', '=', $user_id)->where('target_id', '=', $request->input('target_id'))->firstOrFail();
        $echo->status = $echo->status == 1 ? 0 : 1;
        $echo->save();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = \App\Models\UserAudible::with(['target', 'room', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        \event(new \App\Events\Chatter('audible', $user_id, \App\Http\Resources\UserAudibleResource::collection($sender_audibles)));

        return \response($user);
    }

    public function toggleBotAudible(\Illuminate\Http\Request $request, $user_id)
    {
        $echo = \App\Models\UserAudible::where('user_id', '=', $user_id)->where('bot_id', '=', $request->input('bot_id'))->firstOrFail();
        $echo->status = $echo->status == 1 ? 0 : 1;
        $echo->save();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user_id);
        $sender_audibles = \App\Models\UserAudible::with(['bot', 'room', 'bot'])->where('user_id', $user_id)->get();
        $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
        \cache()->put('user-audibles'.$user_id, $sender_audibles, $expiresAt);
        \event(new \App\Events\Chatter('audible', $user_id, \App\Http\Resources\UserAudibleResource::collection($sender_audibles)));

        return \response($user);
    }

    /* USERS */
    public function updateUserChatStatus(\Illuminate\Http\Request $request, $id)
    {
        $systemUser = \App\Models\User::where('username', 'System')->firstOrFail();
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);
        $status = $this->chatRepository->statusFindOrFail($request->input('status_id'));
        $log = '[url=/users/'.$user->username.']'.$user->username.'[/url] has updated their status to [b]'.$status->name.'[/b]';
        $message = $this->chatRepository->message($systemUser->id, $user->chatroom->id, $log, null);
        $message->save();
        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);
        $user->save();

        return \response($user);
    }

    public function updateUserRoom(\Illuminate\Http\Request $request, $id)
    {
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);
        $room = $this->chatRepository->roomFindOrFail($request->input('room_id'));
        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);
        $user->save();
        $sender_dirty = 0;
        $sender_echoes = \cache()->get('user-echoes'.$id);
        if (! $sender_echoes || ! \is_array($sender_echoes) || (\is_countable($sender_echoes) ? \count($sender_echoes) : 0) < 1) {
            $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$id])->get();
        }
        $sender_listening = false;
        foreach ($sender_echoes as $se => $sender_echo) {
            if ($sender_echo['room_id'] == $room->id) {
                $sender_listening = true;
            }
        }
        if (! $sender_listening) {
            $userEcho = new \App\Models\UserEcho();
            $userEcho->user_id = $id;
            $userEcho->room_id = $room->id;
            $userEcho->save();
            $sender_echoes = \App\Models\UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$id])->get();
            $sender_dirty = 1;
        }
        if ($sender_dirty == 1) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(60);
            \cache()->put('user-echoes'.$id, $sender_echoes, $expiresAt);
            \event(new \App\Events\Chatter('echo', $id, \App\Http\Resources\UserEchoResource::collection($sender_echoes)));
        }

        return \response($user);
    }

    public function updateUserTarget($id)
    {
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);

        return \response($user);
    }

    public function updateBotTarget($id)
    {
        $user = \App\Models\User::with(['chatStatus', 'chatroom', 'group', 'echoes'])->findOrFail($id);

        return \response($user);
    }
}
