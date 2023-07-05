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

namespace App\Http\Livewire;

use App\Bots\NerdBot;
use App\Bots\SystemBot;
use App\Events\EchoCreated;
use App\Events\MessageDeleted;
use App\Events\MessageCreated;
use App\Events\UserEdited;
use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use App\Models\UserEcho;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * @property int $systemChatroomId
 * @property array{
 *      id: int,
 *      user_id: int,
 *      room_id: ?int,
 *      target_id: ?int
 *  } $defaultEcho
 * @property array{
 *       id: int,
 *       user_id: int,
 *       room_id: ?int,
 *       target_id: ?int
 *  } $createEcho
 * @property array<
 *      string,
 *      array{
 *          messages: array<
 *              int,
 *              array{
 *                  id: int,
 *                  user_id: int,
 *                  receiver_id: ?int,
 *                  message: string,
 *                  created_at: string
 *              },
 *          >
 *      }
 *  > $msgs
 * @property array<
 *      int,
 *      array{
 *          id: int,
 *          name: string,
 *          color: string
 *      }
 *  > $statuses
 */
class Chatbox extends Component
{
    /**
     * Current selected chat tab.
     *
     * @var null|array{
     *      id: int,
     *      user_id: int,
     *      room_id: ?int,
     *      target_id: ?int
     *  }
     */
    public ?array $echo = null;

    /**
     * @var User Authenticated user
     */
    public User $user;

    /**
     * @var string Message to send in chatbox
     */
    public string $message = "";

    /**
     * @return array<string, mixed>
     */
    final protected function rules(): array
    {
        return [
            'user.chatroom_id' => [
                'required',
                'exists:chatrooms,id',
            ],
            'user.chat_status_id' => [
                'required',
                'exists:chat_statuses,id',
            ],
            'message' => [
                'required',
                'max:65535',
            ],
            'echo.id' => [
                'integer',
            ],
            'echo.user_id' => [
                'in:'.auth()->id(),
            ],
            'echo.room_id' => [
                'exists:chatrooms,id',
                'prohibits:echo.target_id',
                'required_without:echo.target_id',
            ],
            'echo.target_id' => [
                'exists:users,id',
                'prohibits:echo.room_id',
                'required_without:echo.room_id',
            ]
        ];
    }

    /*
     * Lifecycle hooks
     */

    final public function mount(): void
    {
        $this->user = auth()->user();

        if (ChatStatus::where('id', '=', auth()->user()->chat_status_id)->doesntExist()) {
            auth()->user()->update([
                'chat_status_id' => ChatStatus::first('id')->id,
            ]);
        }

        if (Chatroom::where('id', '=', auth()->user()->chatroom_id)->doesntExist()) {
            auth()->user()->update([
                'chatroom_id' => $this->systemChatroomId,
            ]);
        }

        $this->echo = $this->defaultEcho;
    }

    final public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    final public function updatedUserChatroomId(int $value): void
    {
        $this->echo = UserEcho::firstOrCreate([
            'user_id' => auth()->id(),
            'room_id' => $value,
        ])
            ->only(['id', 'user_id', 'room_id', 'target_id']);
    }

    /**
     * @param array{
     *      id: int,
     *      user_id: int,
     *      target_id: ?int,
     *      room_id: ?int
     *  } $userEcho
     */
    final public function updatedEcho(array $userEcho): void
    {
        abort_unless($userEcho['user_id'] === auth()->id(), 403);

        if ($userEcho['room_id'] !== null) {
            auth()->user()->update([
                'chatroom_id' => $userEcho['room_id'],
            ]);
        }
    }

    final public function updatedUserChatStatusId(int $value): void
    {
        $this->user->save();

        $message = Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => $this->systemChatroomId,
            'message'     => '[url=/users/'.auth()->user()->username.']'.auth()->user()->username.'[/url] has updated their status to [b]'.$this->statuses[$value]['name'].'[/b]',
            'receiver_id' => null,
        ]);

        MessageCreated::dispatch($message);

        auth()
            ->user()
            ->echoes()
            ->whereNotNull('room_id')
            ->each(function (UserEcho $echo) use ($value): void {
                UserEdited::dispatch(auth()->id(), $echo, $value);
            });
    }

    /*
     * Actions
     */

    final public function store(): void
    {
        abort_unless(auth()->user()->can_chat, 403);

        $this->message = trim($this->message);

        $firstWord = strstr($this->message, ' ', true);

        switch ($firstWord) {
            case '/msg':
                [, $username, $message] = preg_split('/ +/', $this->message, 3) + [null, null, ''];

                $this->createEcho($username, $message);

                $this->message = '';

                break;
            case '/gift':
                [, $username, $amount, $message] = preg_split('/ +/', trim($this->message), 4) + [null, null, null, null];

                (new SystemBot())->handle("command gift {$username} {$amount} {$message}");

                break;
            default:
                if ($this->message === '') {
                    return;
                }

                if ($this->echo['target_id'] !== null) {
                    $message = Message::create([
                        'user_id'     => auth()->id(),
                        'chatroom_id' => null,
                        'receiver_id' => $this->echo['target_id'],
                        'message'     => trim($this->message),
                    ]);

                    $echo = UserEcho::firstOrCreate([
                        'user_id'   => $this->echo['target_id'],
                        'target_id' => auth()->id(),
                        'room_id'   => null,
                    ]);

                    if ($echo->wasRecentlyCreated) {
                        EchoCreated::dispatch($echo);

                        sleep(1);
                    }

                    MessageCreated::dispatch($message, auth()->id(), null, $this->echo['target_id']);
                } elseif ($this->echo['room_id'] !== null) {
                    $message = Message::create([
                        'user_id'     => auth()->id(),
                        'chatroom_id' => $this->echo['room_id'],
                        'receiver_id' => null,
                        'message'     => trim($this->message),
                    ]);

                    MessageCreated::dispatch($message, auth()->id(), $this->echo['room_id']);
                } else {
                    abort(500);
                }

                $bots = cache()->remember('bots', 3600, fn () => Bot::select(['id', 'command'])->where('active', '=', true)->get());

                foreach ($bots as $bot) {
                    if ($bot->command === $firstWord) {
                        switch ($bot->id) {
                            case 1: // SystemBot
                                (new SystemBot())->handle($this->message, $this->echo['room_id'], $this->echo['user_id']);

                                break;
                            case 2: // Nerdbot
                                (new NerdBot())->handle($this->message, $this->echo['room_id'], $this->echo['target_id']);

                                break;
                        }
                    }
                }

                $this->message = '';
        }
    }

    final public function destroy(int $id): void
    {
        $message = Message::findOrFail($id);

        abort_unless($message->user_id === auth()->id() || auth()->user()->group->is_modo, 403);

        $message->delete();

        MessageDeleted::dispatch($message);
    }

    final public function destroyEcho(int $id): void
    {
        $echo = UserEcho::findOrFail($id);

        abort_unless($echo->user_id === auth()->id(), 403);

        $echo->delete();

        $this->echo = $this->defaultEcho;
    }

    /**
     * @return array{id: int, user_id: int, room_id: ?int, target_id: ?int}
     */
    final public function createEcho(?string $username, string $message = ''): array
    {
        Validator::make([
            'username' => $username,
        ], [
            'username' => [
                'required',
                Rule::exists('users', 'username')->whereNot('id', auth()->id()),
            ]
        ])->validate();

        $receiver = User::where('username', '=', $username)->sole();

        $echo = UserEcho::firstOrCreate([
            'user_id'   => auth()->id(),
            'target_id' => $receiver->id,
            'room_id'   => null,
        ]);

        if ($echo->wasRecentlyCreated) {
            EchoCreated::dispatch($echo);
        }

        $this->echo = $echo->only(['id', 'user_id', 'target_id', 'room_id']);

        if ($message !== '') {
            $echo = UserEcho::firstOrCreate([
                'user_id'   => $receiver->id,
                'target_id' => auth()->id(),
                'room_id'   => null,
            ]);

            $message = Message::create([
                'user_id'     => auth()->id(),
                'chatroom_id' => null,
                'receiver_id' => $receiver->id,
                'message'     => trim($message),
            ]);

            // Send it twice for more instant feedback for the end user
            MessageCreated::dispatch($message, auth()->id(), null, $receiver->id);

            if ($echo->wasRecentlyCreated) {
                EchoCreated::dispatch($echo);

                sleep(1);
            }
            $this->message = '';

            MessageCreated::dispatch($message, auth()->id(), null, $receiver->id);
        }

        return $this->echo;
    }

    // Custom methods

    final public function getWsChannelName(UserEcho $echo): string
    {
        if ($echo->user_id !== null && $echo->target_id !== null) {
            $ids = [$echo->user_id, $echo->target_id];
            asort($ids);

            return 'messages.pm.'.implode('-', $ids);
        }

        if ($echo->room_id !== null) {
            return 'messages.room.'.$echo->room_id;
        }

        return 'messages.room.'.$this->systemChatroomId;
    }

    // Computed Properties

    /**
     * @return array<
     *       string,
     *       array{
     *           messages: array<
     *               int,
     *               array{
     *                   id: int,
     *                   user_id: int,
     *                   receiver_id: ?int,
     *                   message: string,
     *                   created_at: string
     *               },
     *           >
     *       }
     *   >
     */
    final public function getMsgsProperty(): array
    {
        $channels = [];

        foreach (auth()->user()->echoes()->get() as $echo) {
            $channels[$this->getWsChannelName($echo)]['messages'] = Message::query()
                ->when(
                    $echo->room_id !== null,
                    fn ($query) => $query->where('chatroom_id', '=', $echo->room_id),
                    fn ($query) => $query->where(
                        fn ($query) => $query
                            ->where(fn ($query) => $query->where('user_id', '=', auth()->id())->where('receiver_id', '=', $echo->target_id))
                            ->orWhere(fn ($query) => $query->where('receiver_id', '=', auth()->id())->where('user_id', '=', $echo->target_id)),
                    )
                )
                ->select(['id', 'user_id', 'receiver_id', 'message', 'created_at'])
                ->latest()
                ->limit(config('chat.message_limit'))
                ->get()
                ->keyBy('id')
                ->toArray();
        }

        return $channels;
    }

    /**
     * @return array{id: int, user_id: int, room_id: ?int, target_id: ?int}
     */
    final public function getDefaultEchoProperty(): array
    {
        return UserEcho::query()
            ->where('user_id', '=', auth()->id())
            ->where(
                fn ($query) => $query
                    ->where('room_id', '=', auth()->user()->chatroom_id)
                    ->orWhere('room_id', '=', $this->systemChatroomId)
            )
            ->firstOrCreate([
                'user_id'   => auth()->id(),
                'room_id'   => $this->systemChatroomId,
                'target_id' => null,
            ])
            ->only(['id', 'user_id', 'room_id', 'target_id']);
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          name: string,
     *          color: string
     *      }
     *  >
     */
    final public function getStatusesProperty(): array
    {
        return ChatStatus::all(['id', 'name', 'color'])->keyBy('id')->toArray();
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          name: string
     *      }
     *  >
     */
    final public function getRoomsProperty(): array
    {
        return Chatroom::all(['id', 'name'])->keyBy('id')->toArray();
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          name: string,
     *          color: string
     *      }
     *  >
     */
    final public function getEchoesProperty(): array
    {
        $echoes = auth()
            ->user()
            ->echoes()
            ->get(['id', 'user_id', 'target_id', 'room_id'])
            ->keyBy('id')
            ->toArray();

        return $echoes;
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          name: string,
     *          color: string
     *      }
     *  >
     */
    final public function getAudiblesProperty(): array
    {
        return auth()->user()->audibles()->get(['id', 'user_id', 'target_id', 'room_id'])->keyBy('id')->toArray();
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          name: string,
     *          color: string,
     *          effect: string,
     *          icon: string
     *      }
     *  >
     */
    final public function getGroupsProperty(): array
    {
        return Group::all(['id', 'name', 'color', 'effect', 'icon'])->keyBy('id')->toArray();
    }

    final public function getSystemChatroomIdProperty(): int
    {
        return  Chatroom::query()
            ->where('name', '=', config('chat.system_chatroom'))
            ->orWhere('id', '=', config('chat.system_chatroom'))
            ->sole()
            ->id;
    }

    /**
     * @return array<
     *      int,
     *      array{
     *          id: int,
     *          username: string,
     *          group_id: int,
     *          image: string,
     *          chatroom_id: int,
     *          chat_status_id: int
     *      }
     *  >
     */
    final public function getUsersProperty(): array
    {
        $userIds = [];

        foreach ($this->msgs as $channel) {
            foreach ($channel['messages'] as $message) {
                $userIds[] = $message['user_id'];
                $userIds[] = $message['receiver_id'];
            }
        }

        $echoes = UserEcho::where('user_id', '=', auth()->id())
            ->whereNotNull('target_id')
            ->pluck('target_id')
            ->toArray();

        $userIds = array_merge($userIds, $echoes, [auth()->id()]);

        return User::query()
            ->select([
                'id',
                'username',
                'group_id',
                'image',
                'chatroom_id',
                'chat_status_id'
            ])
            ->whereIn('id', array_unique($userIds))
            ->get()
            ->keyBy('id')
            ->toArray();
    }
}
