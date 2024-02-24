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
use App\Models\Message;
use App\Models\User;
use App\Models\UserAudible;
use App\Models\UserEcho;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Feature\Http\Controllers\API\ChatControllerTest
 */
class ChatController extends Controller
{
    /**
     * ChatController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /* STATUSES */
    public function statuses(): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        return response($this->chatRepository->statuses());
    }

    /* ECHOES */
    public function echoes(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $user = $request->user()->load(['echoes']);

        if (!$user->echoes || (is_countable($user->echoes->toArray()) ? \count($user->echoes->toArray()) : 0) < 1) {
            $userEcho = new UserEcho();
            $userEcho->user_id = $request->user()->id;
            $userEcho->room_id = 1;
            $userEcho->save();
        }

        return UserEchoResource::collection($this->chatRepository->echoes($request->user()->id));
    }

    /* AUDIBLES */
    public function audibles(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $user = $request->user()->load(['audibles']);

        if (!$user->audibles || (is_countable($user->audibles->toArray()) ? \count($user->audibles->toArray()) : 0) < 1) {
            $userAudible = new UserAudible();
            $userAudible->user_id = $request->user()->id;
            $userAudible->room_id = 1;
            $userAudible->status = true;
            $userAudible->save();
        }

        return UserAudibleResource::collection($this->chatRepository->audibles($request->user()->id));
    }

    /* BOTS */
    public function bots(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return BotResource::collection($this->chatRepository->bots());
    }

    /* ROOMS */
    public function rooms(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ChatRoomResource::collection($this->chatRepository->rooms());
    }

    public function config(): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        return response($this->chatRepository->config());
    }

    /* MESSAGES */
    public function messages($roomId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ChatMessageResource::collection($this->chatRepository->messages($roomId));
    }

    /* MESSAGES */
    public function privateMessages(Request $request, $targetId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ChatMessageResource::collection($this->chatRepository->privateMessages($request->user()->id, $targetId));
    }

    /* MESSAGES */
    public function botMessages(Request $request, $botId): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $runbot = null;
        $bot = Bot::findOrFail($botId);

        if ($bot->is_systembot) {
            $runbot = new SystemBot($this->chatRepository);
        } elseif ($bot->is_nerdbot) {
            $runbot = new NerdBot($this->chatRepository);
        }

        $runbot->process('message', $request->user(), '', 0);

        return ChatMessageResource::collection($this->chatRepository->botMessages($request->user()->id, $bot->id));
    }

    public function createMessage(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|bool|ChatMessageResource
    {
        $bot = null;
        $user = $request->user();

        $userId = $user->id;
        $receiverId = $request->input('receiver_id');
        $roomId = $request->input('chatroom_id');
        $botId = $request->input('bot_id');
        $message = $request->input('message');
        $targeted = $request->input('targeted');
        $save = $request->get('save');

        if ($user->can_chat === false) {
            return response('error', 401);
        }

        // Temp Fix For HTMLPurifier
        if ($message === '<') {
            return response('error', 401);
        }

        $botDirty = 0;
        $bots = cache()->get('bots');

        if (!$bots || !\is_array($bots) || \count($bots) < 1) {
            $bots = Bot::where('active', '=', 1)->oldest('position')->get();
            $botDirty = 1;
        }

        if ($botDirty == 1) {
            $expiresAt = Carbon::now()->addMinutes(60);
            cache()->put('bots', $bots, $expiresAt);
        }

        $which = null;
        $target = null;
        $runbot = null;
        $trip = 'msg';

        if ($message && str_starts_with((string) $message, '/'.$trip)) {
            $which = 'skip';
            $command = @explode(' ', (string) $message);

            if (\array_key_exists(1, $command)) {
                $receiverId = User::where('username', 'like', $command[1])->sole()->id;
                $clone = $command;
                array_shift($clone);
                array_shift($clone);
                $message = trim(implode(' ', $clone));
            }

            $botId = 1;
        }

        $trip = 'gift';

        if ($message && str_starts_with((string) $message, '/'.$trip)) {
            $which = 'echo';
            $target = 'system';
            $message = '/bot gift'.substr((string) $message, \strlen($trip) + 1, \strlen((string) $message));
        }

        if ($target == 'system') {
            $runbot = new SystemBot($this->chatRepository);
        }

        if ($which == null) {
            foreach ($bots as $bot) {
                if ($message && str_starts_with((string) $message, '/'.$bot->command)) {
                    $which = 'echo';
                } elseif ($message && str_starts_with((string) $message, '!'.$bot->command)) {
                    $which = 'public';
                } elseif ($message && str_starts_with((string) $message, '@'.$bot->command)) {
                    $message = substr((string) $message, 1 + \strlen((string) $bot->command), \strlen((string) $message));
                    $which = 'private';
                } elseif ($message && $receiverId == 1 && $bot->id == $botId) {
                    if (str_starts_with((string) $message, '/'.$bot->command)) {
                        $message = substr((string) $message, 1 + \strlen((string) $bot->command), \strlen((string) $message));
                    }

                    if ($message && str_starts_with((string) $message, '!'.$bot->command)) {
                        $message = substr((string) $message, 1 + \strlen((string) $bot->command), \strlen((string) $message));
                    }

                    if ($message && str_starts_with((string) $message, '@'.$bot->command)) {
                        $message = substr((string) $message, 1 + \strlen((string) $bot->command), \strlen((string) $message));
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
                $runbot = new SystemBot($this->chatRepository);
            } elseif ($bot->is_nerdbot) {
                $runbot = new NerdBot($this->chatRepository);
            } elseif ($bot->is_casinobot) {
                $runbot = new CasinoBot($this->chatRepository);
            }
        }

        if ($runbot !== null) {
            return $runbot->process($which, $request->user(), $message, 0);
        }

        $echo = false;

        if ($receiverId && $receiverId > 0) {
            $senderDirty = 0;
            $receiverDirty = 0;
            $senderEchoes = cache()->get('user-echoes'.$userId);
            $receiverEchoes = cache()->get('user-echoes'.$receiverId);

            if (!$senderEchoes || !\is_array($senderEchoes) || \count($senderEchoes) < 1) {
                $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $userId)->get();
            }

            if (!$receiverEchoes || !\is_array($receiverEchoes) || \count($receiverEchoes) < 1) {
                $receiverEchoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiverId])->get();
            }

            $senderListening = false;

            foreach ($senderEchoes as $senderEcho) {
                if ($senderEcho['target_id'] == $receiverId) {
                    $senderListening = true;
                }
            }

            if (!$senderListening) {
                $senderPort = new UserEcho();
                $senderPort->user_id = $userId;
                $senderPort->target_id = $receiverId;
                $senderPort->save();
                $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $userId)->get();
                $senderDirty = 1;
            }

            $receiverListening = false;

            foreach ($receiverEchoes as $receiverEcho) {
                if ($receiverEcho['target_id'] == $userId) {
                    $receiverListening = true;
                }
            }

            if (!$receiverListening) {
                $receiverPort = new UserEcho();
                $receiverPort->user_id = $receiverId;
                $receiverPort->target_id = $userId;
                $receiverPort->save();
                $receiverEchoes = UserEcho::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiverId])->get();
                $receiverDirty = 1;
            }

            if ($senderDirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$userId, $senderEchoes, $expiresAt);
                event(new Chatter('echo', $userId, UserEchoResource::collection($senderEchoes)));
            }

            if ($receiverDirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-echoes'.$receiverId, $receiverEchoes, $expiresAt);
                event(new Chatter('echo', $receiverId, UserEchoResource::collection($receiverEchoes)));
            }

            $senderDirty = 0;
            $receiverDirty = 0;
            $senderAudibles = cache()->get('user-audibles'.$userId);
            $receiverAudibles = cache()->get('user-audibles'.$receiverId);

            if (!$senderAudibles || !\is_array($senderAudibles) || \count($senderAudibles) < 1) {
                $senderAudibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $userId)->get();
            }

            if (!$receiverAudibles || !\is_array($receiverAudibles) || \count($receiverAudibles) < 1) {
                $receiverAudibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiverId])->get();
            }

            $senderListening = false;

            foreach ($senderAudibles as $senderEcho) {
                if ($senderEcho['target_id'] == $receiverId) {
                    $senderListening = true;
                }
            }

            if (!$senderListening) {
                $senderPort = new UserAudible();
                $senderPort->user_id = $userId;
                $senderPort->target_id = $receiverId;
                $senderPort->status = false;
                $senderPort->save();
                $senderAudibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $userId)->get();
                $senderDirty = 1;
            }

            $receiverListening = false;

            foreach ($receiverAudibles as $receiverEcho) {
                if ($receiverEcho['target_id'] == $userId) {
                    $receiverListening = true;
                }
            }

            if (!$receiverListening) {
                $receiverPort = new UserAudible();
                $receiverPort->user_id = $receiverId;
                $receiverPort->target_id = $userId;
                $receiverPort->status = false;
                $receiverPort->save();
                $receiverAudibles = UserAudible::with(['room', 'target', 'bot'])->whereRaw('user_id = ?', [$receiverId])->get();
                $receiverDirty = 1;
            }

            if ($senderDirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$userId, $senderAudibles, $expiresAt);
                event(new Chatter('audible', $userId, UserAudibleResource::collection($senderAudibles)));
            }

            if ($receiverDirty == 1) {
                $expiresAt = Carbon::now()->addMinutes(60);
                cache()->put('user-audibles'.$receiverId, $receiverAudibles, $expiresAt);
                event(new Chatter('audible', $receiverId, UserAudibleResource::collection($receiverAudibles)));
            }

            $roomId = 0;
            $ignore = $botId > 0 && $receiverId == 1 ? true : null;
            $save = true;
            $echo = true;
            $message = $this->chatRepository->privateMessage($userId, $roomId, $message, $receiverId, null, $ignore);
        } else {
            $receiverId = null;
            $botId = null;
            $message = $this->chatRepository->message($userId, $roomId, $message, $receiverId, $botId);
        }

        if (!$save) {
            $message->delete();
        }

        if ($save && $echo) {
            return new ChatMessageResource($message);
        }

        return response('success');
    }

    public function deleteMessage(Request $request, $id): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $message = Message::find($id);

        abort_unless($request->user()->id === $message->user_id || $request->user()->group->is_modo, 403);

        $changedByStaff = $request->user()->id !== $message->user_id;

        abort_if($changedByStaff && !$request->user()->group->is_owner && $request->user()->group->level <= $message->user->group->level, 403);

        $this->chatRepository->deleteMessage($id);

        return response('success');
    }

    public function deleteRoomEcho(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        UserEcho::where('user_id', '=', $user->id)->where('room_id', '=', $request->input('room_id'))->delete();

        $user->load(['chatStatus', 'chatroom', 'group', 'echoes']);
        $room = $this->chatRepository->roomFindOrFail($request->input('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user->id, $senderEchoes, $expiresAt);
        event(new Chatter('echo', $user->id, UserEchoResource::collection($senderEchoes)));

        return response($user);
    }

    public function deleteTargetEcho(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        UserEcho::where('user_id', '=', $user->id)->where('target_id', '=', $request->input('target_id'))->delete();

        $user->load(['chatStatus', 'chatroom', 'group', 'echoes']);
        $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user->id, $senderEchoes, $expiresAt);
        event(new Chatter('echo', $user->id, UserEchoResource::collection($senderEchoes)));

        return response($user);
    }

    public function deleteBotEcho(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        UserEcho::where('user_id', '=', $user->id)->where('bot_id', '=', $request->input('bot_id'))->delete();

        $user->load(['chatStatus', 'chatroom', 'group', 'echoes']);
        $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-echoes'.$user->id, $senderEchoes, $expiresAt);
        event(new Chatter('echo', $user->id, UserEchoResource::collection($senderEchoes)));

        return response($user);
    }

    public function toggleRoomAudible(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        $echo = UserAudible::where('user_id', '=', $user->id)->where('room_id', '=', $request->input('room_id'))->sole();
        $echo->status = !$echo->status;
        $echo->save();

        $user->load(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles']);
        $senderAudibles = UserAudible::with(['room', 'target', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user->id, $senderAudibles, $expiresAt);
        event(new Chatter('audible', $user->id, UserAudibleResource::collection($senderAudibles)));

        return response($user);
    }

    public function toggleTargetAudible(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        $echo = UserAudible::where('user_id', '=', $user->id)->where('target_id', '=', $request->input('target_id'))->sole();
        $echo->status = !$echo->status;
        $echo->save();

        $user->load(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles']);
        $senderAudibles = UserAudible::with(['target', 'room', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user->id, $senderAudibles, $expiresAt);
        event(new Chatter('audible', $user->id, UserAudibleResource::collection($senderAudibles)));

        return response($user);
    }

    public function toggleBotAudible(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        $echo = UserAudible::where('user_id', '=', $user->id)->where('bot_id', '=', $request->input('bot_id'))->sole();
        $echo->status = !$echo->status;
        $echo->save();

        $user->load(['chatStatus', 'chatroom', 'group', 'audibles', 'audibles'])->findOrFail($user->id);
        $senderAudibles = UserAudible::with(['bot', 'room', 'bot'])->where('user_id', $user->id)->get();

        $expiresAt = Carbon::now()->addMinutes(60);
        cache()->put('user-audibles'.$user->id, $senderAudibles, $expiresAt);
        event(new Chatter('audible', $user->id, UserAudibleResource::collection($senderAudibles)));

        return response($user);
    }

    /* USERS */
    public function updateUserChatStatus(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $systemUser = User::where('username', 'System')->sole();

        $user = $request->user();
        $user->load(['chatStatus', 'chatroom', 'group', 'echoes']);
        $status = $this->chatRepository->statusFindOrFail($request->input('status_id'));

        $this->chatRepository->systemMessage('[url=/users/'.$user->username.']'.$user->username.'[/url] has updated their status to [b]'.$status->name.'[/b]');

        $user->chatStatus()->dissociate();
        $user->chatStatus()->associate($status);
        $user->save();

        return response($user);
    }

    public function updateUserRoom(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user();
        $user->load(['chatStatus', 'chatroom', 'group', 'echoes']);
        $room = $this->chatRepository->roomFindOrFail($request->input('room_id'));

        $user->chatroom()->dissociate();
        $user->chatroom()->associate($room);

        $user->save();

        $senderDirty = 0;
        $senderEchoes = cache()->get('user-echoes'.$user->id);

        if (!$senderEchoes || !\is_array($senderEchoes) || \count($senderEchoes) < 1) {
            $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', '=', $user->id)->get();
        }

        $senderListening = false;

        foreach ($senderEchoes as $senderEcho) {
            if ($senderEcho['room_id'] == $room->id) {
                $senderListening = true;
            }
        }

        if (!$senderListening) {
            $userEcho = new UserEcho();
            $userEcho->user_id = $user->id;
            $userEcho->room_id = $room->id;
            $userEcho->save();
            $senderEchoes = UserEcho::with(['room', 'target', 'bot'])->where('user_id', '=', $user->id)->get();
            $senderDirty = 1;
        }

        if ($senderDirty == 1) {
            $expiresAt = Carbon::now()->addMinutes(60);
            cache()->put('user-echoes'.$user->id, $senderEchoes, $expiresAt);
            event(new Chatter('echo', $user->id, UserEchoResource::collection($senderEchoes)));
        }

        return response($user);
    }

    public function updateUserTarget(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user()->load(['chatStatus', 'chatroom', 'group', 'echoes']);

        return response($user);
    }

    public function updateBotTarget(Request $request): \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
    {
        $user = $request->user()->load(['chatStatus', 'chatroom', 'group', 'echoes']);

        return response($user);
    }
}
