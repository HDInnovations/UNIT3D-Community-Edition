<?php

declare(strict_types=1);

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
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @phpstan-type AudibleResource array{
 *     id: int,
 *     user_id: int,
 *     target_id: ?int,
 *     room_id: ?int,
 * }
 * @phpstan-type EchoResource array{
 *      id: int,
 *      user_id: int,
 *      target_id: ?int,
 *      room_id: ?int,
 *  }
 * @phpstan-type GroupResource array{
 *     id: int,
 *     name: string,
 *     color: string,
 *     effect: string,
 *     icon: string,
 * }
 * @phpstan-type StatusResource array{
 *     id: int,
 *     name: string,
 *     color: string,
 * }
 * @phpstan-type RoomResource array{
 *     id: int,
 *     name: string,
 * }
 * @phpstan-type UserResource array{
 *     id: int,
 *     username: string,
 *     group_id: int,
 *     image: string,
 *     chatroom_id: int,
 *     chat_status_id: int,
 * }
 * @phpstan-type MessageResource array{
 *     id: int,
 *     user_id: int,
 *     receiver_id: ?int,
 *     message: string,
 *     created_at: string,
 * }
 */
class Chatbox extends Component
{
    /** @var ?EchoResource Current selected chat tab */
    public ?array $echo = null;

    /** @var User Authenticated user */
    public User $user;

    /** @var string Message to send in chatbox */
    public string $message = '';

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
            ],
        ];
    }

    /*
     * Lifecycle hooks
     */

    final public function mount(): void
    {
        $this->user = auth()->user();

        if ($this->user->chatStatus()->doesntExist()) {
            $this->user->chatStatus()->associate(ChatStatus::first());
        }

        if ($this->user->chatroom()->doesntExist()) {
            $this->user->chatroom()->associate($this->systemChatroomId);
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

    /** @param EchoResource $userEcho */
    final public function updatedEcho(array $userEcho): void
    {
        abort_unless($userEcho['user_id'] === auth()->id(), 403);

        if ($userEcho['room_id'] !== null) {
            auth()->user()->chatroom()->associate($userEcho['room_id']);
        }
    }

    final public function updatedUserChatStatusId(int $value): void
    {
        $this->user->save();

        $message = Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => $this->systemChatroomId,
            'message'     => '[url=/users/'.auth()->user()->username.']'.auth()->user()->username.'[/url] has updated their status to [b]'.$this->statuses[$value]['name'].'[/b]',
        ]);

        MessageCreated::dispatch($message);

        auth()->user()->echoes()->has('room')->each(function ($echo) use ($value): void {
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

        if ($this->message === '') {
            return;
        }

        $firstWord = strstr($this->message, ' ', true);

        switch ($firstWord) {
            case '/msg':
                [, $username, $message] = preg_split('/ +/', $this->message, 3) + [null, null, ''];

                $this->createEcho($username, $message);

                $this->reset('message');

                break;
            case '/gift':
                [, $username, $amount, $message] = preg_split('/ +/', trim($this->message), 4) + [null, null, null, null];

                (new SystemBot())->handle("command gift {$username} {$amount} {$message}");

                break;
            default:
                if ($this->echo['target_id'] !== null) {
                    $message = Message::create([
                        'user_id'     => auth()->id(),
                        'receiver_id' => $this->echo['target_id'],
                        'message'     => trim($this->message),
                    ]);

                    $echo = UserEcho::firstOrCreate([
                        'user_id'   => $this->echo['target_id'],
                        'target_id' => auth()->id(),
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
                        'message'     => trim($this->message),
                    ]);

                    MessageCreated::dispatch($message, auth()->id(), $this->echo['room_id']);
                } else {
                    abort(500);
                }

                $bots = cache()->remember('bots', 3600, fn () => Bot::select(['id', 'command'])->where('active', '=', true)->get());

                foreach ($bots as $bot) {
                    if ($bot->command === $firstWord) {
                        match ($bot->id) {
                            1       => (new SystemBot())->handle($this->message, $this->echo['room_id'], $this->echo['user_id']),
                            2       => (new NerdBot())->handle($this->message, $this->echo['room_id'], $this->echo['target_id']),
                            default => null,
                        };
                    }
                }

                $this->reset('message');
        }
    }

    final public function destroy(Message $message): void
    {
        abort_unless($message->user_id === auth()->id() || auth()->user()->group->is_modo, 403);

        $message->delete();

        MessageDeleted::dispatch($message);
    }

    final public function destroyEcho(UserEcho $echo): void
    {
        abort_unless($echo->user_id === auth()->id(), 403);

        $echo->delete();

        $this->echo = $this->defaultEcho;
    }

    /** @return EchoResource */
    final public function createEcho(?string $username, string $message = ''): array
    {
        Validator::make([
            'username' => $username,
        ], [
            'username' => [
                'required',
                Rule::exists('users', 'username')->whereNot('id', auth()->id()),
            ],
        ])->validate();

        $receiver = User::where('username', '=', $username)->sole();

        $echo = UserEcho::firstOrCreate([
            'user_id'   => auth()->id(),
            'target_id' => $receiver->id,
        ]);

        if ($echo->wasRecentlyCreated) {
            EchoCreated::dispatch($echo);
        }

        $this->echo = $echo->only(['id', 'user_id', 'target_id', 'room_id']);

        if ($message !== '') {
            $echo = UserEcho::firstOrCreate([
                'user_id'   => $receiver->id,
                'target_id' => auth()->id(),
            ]);

            $message = Message::create([
                'user_id'     => auth()->id(),
                'receiver_id' => $receiver->id,
                'message'     => trim($message),
            ]);

            // Send it twice for more instant feedback for the end user
            MessageCreated::dispatch($message, auth()->id(), null, $receiver->id);

            if ($echo->wasRecentlyCreated) {
                EchoCreated::dispatch($echo);

                sleep(1);
            }

            $this->reset('message');

            MessageCreated::dispatch($message, auth()->id(), null, $receiver->id);
        }

        return $this->echo;
    }

    // Custom methods

    final public function getWsChannelName(UserEcho $echo): string
    {
        return match (true) {
            $echo->target_id !== null => 'messages.pm.'.min($echo->target_id, $echo->user_id).'-'.max($echo->target_id, $echo->user_id),
            $echo->room_id !== null   => 'messages.room.'.$echo->room_id,
            default                   => 'messages.room.'.$this->systemChatroomId,
        };
    }

    // Computed Properties

    /** @return array<string, array{messages: array<int, MessageResource>}> */
    #[Computed]
    final public function msgs(): array
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
                ->orderByDesc('id')
                ->limit(config('chat.message_limit'))
                ->get()
                ->keyBy('id')
                ->toArray();
        }

        return $channels;
    }

    /** @return EchoResource */
    #[Computed]
    final public function defaultEcho(): array
    {
        return UserEcho::query()
            ->where('user_id', '=', auth()->id())
            ->whereIn('room_id', [auth()->user()->chatroom_id, $this->systemChatroomId])
            ->firstOrCreate([
                'user_id'   => auth()->id(),
                'room_id'   => $this->systemChatroomId,
                'target_id' => null,
            ])
            ->only(['id', 'user_id', 'room_id', 'target_id']);
    }

    /** @return array<int, StatusResource> */
    #[Computed]
    final public function statuses(): array
    {
        return ChatStatus::all(['id', 'name', 'color'])->keyBy('id')->toArray();
    }

    /** @return array<int, RoomResource> */
    #[Computed]
    final public function rooms(): array
    {
        return Chatroom::all(['id', 'name'])->keyBy('id')->toArray();
    }

    /** @return array<int, EchoResource> */
    #[Computed]
    final public function echoes(): array
    {
        return auth()->user()->echoes()->get(['id', 'user_id', 'target_id', 'room_id'])->keyBy('id')->toArray();
    }

    /** @return array<int, AudibleResource> */
    #[Computed]
    final public function audibles(): array
    {
        return auth()->user()->audibles()->get(['id', 'user_id', 'target_id', 'room_id'])->keyBy('id')->toArray();
    }

    /** @return array<int, GroupResource> */
    #[Computed]
    final public function groups(): array
    {
        return Group::all(['id', 'name', 'color', 'effect', 'icon'])->keyBy('id')->toArray();
    }

    #[Computed]
    final public function systemChatroomId(): int
    {
        $config = config('chat.system_chatroom');

        return Chatroom::query()->where(\is_int($config) ? 'id' : 'name', '=', $config)->soleValue('id');
    }

    /** @return array<int, UserResource> */
    #[Computed]
    final public function users(): array
    {
        $messageUserIds = collect($this->msgs)->pluck('messages.*.user_id')->flatten()->toArray();

        $echoUserIds = auth()->user()->echoes()->has('target')->pluck('target_id')->toArray();

        $userIds = array_merge($messageUserIds, $echoUserIds, [auth()->id()]);

        return User::query()
            ->select(['id', 'username', 'group_id', 'image', 'chatroom_id', 'chat_status_id'])
            ->whereIntegerInRaw('id', array_unique($userIds))
            ->get()
            ->keyBy('id')
            ->toArray();
    }
}
