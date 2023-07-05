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
use App\Events\CreateMessage;
use App\Events\DestroyMessage;
use App\Events\NewChat;
use App\Models\Bot;
use App\Models\Chatroom;
use App\Models\ChatStatus;
use App\Models\Message;
use App\Models\User;
use App\Models\UserEcho;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Messages extends Component
{
    public ?int $chatroomId;

    /** @var array{
     *      id: int,
     *      user_id: int,
     *      room_id: ?int,
     *      target_id: ?int,
     *  } The current chatroom/user/bot tab selected at the top of the chatbox
     */
    public array $echo;

    public ?int $chatStatusId;

    private User $user;

    public string $message = "";

    /**
     * Intentional misname due to Livewire having a protected $messages property for validation already.
     * @var array<
     *      int,
     *      array{
     *          message: string
     *      }
     *  >
     */
    public array $msgs;

    /** @var array<
     *      int,
     *      array{
     *          id: int,
     *          username: string
     *      }
     *  >
     */
    public array $users;

    /** @var array<
     *      int,
     *      array{
     *          id: int,
     *          name: string,
     *          color: string,
     *          icon: string
     *      }
     *  >
     */
    public array $statuses;

    /** @var array<
     *      int,
     *      array{
     *          id: int,
     *          name: string
     *      }
     *  >
     */
    public array $rooms;

    /** @var array<
     *      int,
     *      array{
     *          id: int,
     *          user_id: int,
     *          room_id: ?int,
     *          target_id: ?int,
     *      }
     *  > The chatroom/user tabs at the top of the chatbox
     */
    public array $echoes;

    /** @var array<
     *      int,
     *      array{
     *          id: int,
     *          user_id: int,
     *          room_id: ?int,
     *          target_id: ?int,
     *      }
     *  > The audio notifications per chatroom/user tab
     */
    public array $audibles;

    public ?int $systemChatroomId = null;

    public ?int $systemUserId = null;

    /**
     * @return array<string, mixed>
     */
    final protected function rules(): array
    {
        return [
            'chatroomId' => [
                'required',
                'exists:chatrooms,id',
            ],
            'echo.user_id' => [
                Rule::exists('user_echoes', 'id')->where('user_id', auth()->id()),
            ],
            'message' => [
                'required',
                'max:65535',
            ],
        ];
    }

    /*
     * Lifecycle hooks
     */

    final public function mount(): void
    {
        $this->user = auth()->user();
        $this->chatroomId = $this->user->chatroom_id;
        $this->systemChatroomId = Chatroom::query()
            ->where('name', '=', config('chat.system_chatroom'))
            ->orWhere('id', '=', config('chat.system_chatroom'))
            ->sole()
            ->id;
        $this->rooms = Chatroom::all(['id', 'name'])->keyBy('id')->toArray();
        $this->statuses = ChatStatus::all(['id', 'name', 'color', 'icon'])->keyBy('id')->toArray();
        $this->echoes = $this->user->echoes->load(['user', 'target', 'room'])->keyBy('id')->toArray();
        $this->audibles = $this->user->audibles->load(['user', 'target', 'room'])->keyBy('id')->toArray();

        if (ChatStatus::where('id', '=', $this->user->chat_status_id)->doesntExist()) {
            $this->user->update([
                'chat_status_id' => ChatStatus::first('id')->id,
            ]);
        }

        $this->chatStatusId = $this->user->chat_status_id;

        $this->initializeEcho();
        $this->reloadMessages();
    }

    final public function updatedChatroomId(int $value): void
    {
        $this->validateOnly('chatroomId');

        auth()->user()->update([
            'chatroom_id' => $value,
        ]);

        $echo = UserEcho::firstOrCreate([
            'user_id' => auth()->id(),
            'room_id' => $value,
        ]);

        if ($echo->wasRecentlyCreated) {
            $this->echoes[$echo->id] = $echo->load(['user', 'target', 'room'])->toArray();
        }

        $this->dispatchBrowserEvent('updatedEchoId', [
            'previous' => $this->echo['id'],
            'new'      => $echo->id,
        ]);

        $this->echo = $echo->toArray();

        $this->reloadMessages();
    }

    final public function updatedEchoId(int $id): void
    {
        $this->dispatchBrowserEvent('updatedEchoId', [
            'previous' => $this->echo,
            'new'      => $this->echoes[$id],
        ]);

        $this->echo = $this->echoes[$id];

        if ($this->echo['room_id'] !== null) {
            auth()->user()->update([
                'chatroom_id' => $this->echo['room_id'],
            ]);
        }

        $this->reloadMessages();
    }

    final public function updatedChatStatusId(int $value): void
    {
        auth()->user()->update([
            'chat_status_id' => $value,
        ]);

        $message = Message::create([
            'user_id'     => User::SYSTEM_USER_ID,
            'chatroom_id' => $this->systemChatroomId,
            'message'     => '[url=/users/'.auth()->user()->username.']'.auth()->user()->username.'[/url] has updated their status to [b]'.$this->statuses[$value]['name'].'[/b]',
            'receiver_id' => null,
        ]);

        CreateMessage::dispatch($message, $this->echo);
    }

    /*
     * Listeners
     */

    /**
     * @var string[]
     */
    protected $listeners = [
        'updateChatbox',
        'newChat',
    ];

    /**
     * @param  mixed[] $event
     * @return void
     */
    final public function updateChatbox(array $event): void
    {
        switch ($event['type']) {
            case 'MESSAGE_CREATE':
                $this->msgs[$event['message']['id']] = $event['message'];

                break;
            case 'MESSAGE_DESTROY':
                unset($this->msgs[$event['message']['id']]);

                break;
            case 'USER_LIST':
                /** @phpstan-ignore-next-line */
                $this->users = collect($event['users'])->keyBy('id')->toArray();

                break;
            case 'USER_JOIN':
                $this->users[$event['user']['id']] = $event['user'];

                break;
            case 'USER_LEAVE':
                unset($this->users[$event['user']['id']]);

                break;
        }
    }

    /**
     * @param  mixed[] $event
     * @return void
     */
    final public function newChat(array $event): void
    {
        if (!\array_key_exists('userId', $event) || !\is_int($event['userId'])) {
            return;
        }

        $echo = UserEcho::firstOrCreate([
            'user_id'   => auth()->id(),
            'target_id' => $event['userId'],
            'room_id'   => null,
        ]);

        if ($echo->wasRecentlyCreated) {
            $this->echoes[$echo->id] = $echo->load(['user', 'target', 'room'])->toArray();
        }
    }

    /*
     * Actions
     */

    final public function store(): void
    {
        abort_unless(auth()->user()->can_chat, 401);

        $this->message = trim($this->message);

        $firstWord = strstr($this->message, ' ', true);

        switch ($firstWord) {
            case '/msg':
                [, $username, $message] = preg_split('/ +/', $this->message, 3) + [null, null, ''];

                $this->createNewChat($username, $message);

                $this->message = '';

                break;
            case '/gift':
                [, $username, $amount, $message] = preg_split('/ +/', trim($this->message), 4) + [null, null, null, null];

                (new SystemBot())->handle('gift', [
                    'username' => $username,
                    'amount'   => $amount,
                    'message'  => $message,
                ], $this->echo);

                break;
            default:
                $message = Message::create([
                    'user_id'     => auth()->id(),
                    'chatroom_id' => $this->echo['room_id'] ?? 0,
                    'receiver_id' => $this->echo['target_id'] ?? null,
                    'message'     => trim($this->message),
                ]);

                CreateMessage::dispatch($message, $this->echo);

                $bots = cache()->remember('bots', 3600, fn () => Bot::select(['id', 'command'])->where('active', '=', true)->get());

                foreach ($bots as $bot) {
                    if ($bot->command === $firstWord) {
                        switch ($bot->id) {
                            case 1: // SystemBot
                                [, $command] = preg_split('/ +/', trim($this->message), 2) + [null, null];

                                (new SystemBot())->handle($command, [], $this->echo);

                                break;
                            case 2: // Nerdbot
                                [, $command] = preg_split('/ +/', trim($this->message), 2) + [null, null];

                                (new NerdBot())->handle($command, $this->echo);

                                break;
                        }
                    }
                }

                $this->message = '';
        }
    }

    final public function destroy(Message $message): void
    {
        abort_unless($message->user_id === auth()->id() || auth()->user()->group->is_modo, 403);

        $message->delete();

        DestroyMessage::dispatch($message);
    }

    final public function destroyEcho(UserEcho $echo): void
    {
        abort_unless($echo->user_id === auth()->id(), 403);

        $echo->delete();

        unset($this->echoes[$echo->id]);

        $this->initializeEcho();
        $this->reloadMessages();
    }

    final public function createNewChat(?string $username, string $message = ''): void
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
            $this->echoes[$echo->id] = $echo->load(['user', 'target', 'room'])->toArray();
        }

        $this->dispatchBrowserEvent('updatedEchoId', [
            'previous' => $this->echo['id'],
            'new'      => $echo->id,
        ]);

        $this->echo = $echo->toArray();

        $this->reloadMessages();

        if ($message !== '') {
            $message = Message::create([
                'user_id'     => auth()->id(),
                'chatroom_id' => 0,
                'receiver_id' => $receiver->id,
                'message'     => trim($message),
            ]);

            $this->message = '';

            $this->msgs[$message->id] = $message->toArray();

            CreateMessage::dispatch($message, $this->echo);

            $echo = UserEcho::firstOrCreate([
                'user_id'   => $receiver->id,
                'target_id' => auth()->id(),
                'room_id'   => null,
            ]);

            if ($echo->wasRecentlyCreated) {
                NewChat::dispatch($receiver->id);
            }
        }
    }

    // Custom methods

    final public function reloadMessages(): void
    {
        abort_unless(UserEcho::where('id', '=', $this->echo['id'])->where('user_id', '=', auth()->id())->exists(), 403);

        $this->msgs = Message::query()
            ->when(
                $this->echo['room_id'] !== null,
                fn ($query) => $query->where('chatroom_id', '=', $this->echo['room_id']),
                fn ($query) => $query
                    ->where(fn ($query) => $query->where('user_id', '=', auth()->id())->where('receiver_id', '=', $this->echo['target_id']))
                    ->orWhere(fn ($query) => $query->where('receiver_id', '=', auth()->id())->where('user_id', '=', $this->echo['target_id'])),
            )
            ->select(['id', 'user_id', 'message', 'created_at'])
            ->with(['user:id,username,group_id,image,chat_status_id' => [
                'group:id,name,icon,color,effect',
                'chatStatus:id,color,name'
            ]])
            ->latest()
            ->limit(config('chat.message_limit'))
            ->get()
            ->keyBy('id')
            ->toArray();
    }

    final public function initializeEcho(): void
    {
        $echo = collect($this->echoes)->firstWhere('room_id', '=', auth()->user()->chatroom_id)
            ?? collect($this->echoes)->firstWhere('room_id', '=', $this->systemChatroomId);

        if ($echo === null) {
            $echo = UserEcho::create([
                'user_id'   => auth()->id(),
                'room_id'   => $this->systemChatroomId,
                'target_id' => null,
            ])
                ->toArray();
            $this->echoes[$echo['id']] = $echo;
        }

        $this->echo = $echo;
    }
}
