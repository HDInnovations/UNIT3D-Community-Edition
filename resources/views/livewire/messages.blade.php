<section class="panelV2 chatbox" x-data="{ echoId: @entangle('echo.id'), userMenuOpen: true }" >
    <header class="panel__header">
        <h2 class="panel__heading">
            Chatbox
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="user-list"
                        class="form__checkbox"
                        required
                        type="checkbox"
                        x-model="userMenuOpen"
                    >
                    <label class="form__label" for="user-list">
                        {{ __('common.users') }}
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="chatroomId"
                        class="form__select"
                        required
                        wire:model="chatroomId"
                    >
                        @foreach($rooms ?? [] as $id => $room)
                            <option value="{{ $id }}">{{ $room['name'] }}</option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="chatroomId">
                        Chatroom
                    </label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="chatStatusId"
                        class="form__select"
                        required
                        wire:model="chatStatusId"
                    >
                        @foreach($statuses ?? [] as $id => $status)
                            <option value="{{ $id }}">{{ $status['name'] }}</option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="chatStatusId">
                        Status
                    </label>
                </div>
            </div>
        </div>
    </header>
    <menu class="panel__tabs">
        @foreach (collect($echoes)->sortBy('created_at')->toArray() ?? [] as $id => $echo)
            <li
                class="panel__tab chatbox__tab"
                role="tab"
                x-bind:class="echoId === {{ $id }} && 'panel__tab--active'"
                x-cloak
                x-on:mousedown="
                    if ($event && ($event.which === 2 || $event.buttons === 4)) {
                        @this.destroyEcho({{ $id}});
                    } else {
                        echoId = {{ $id }};
                    }
                "
            >
                <span>
                    {{ $echo['room']['name'] ?? $echo['target']['username'] ?? $echo['bot']['name'] ?? 'Unknown' }}
                </span>
                @if ($id === $this->echo['id'])
                    <button class="chatbox__tab-delete-button" wire:click.prevent="destroyEcho({{ $id }})">
                        <i class="{{ config('other.font-awesome') }} fa-times chatbox__tab-delete-icon"></i>
                    </button>
                @endif
            </li>
        @endforeach
    </menu>
    <div class="chatbox__chatroom">
        <div class="chatroom__messages--wrapper">
            <ul class="chatroom__messages">
                @foreach($msgs ?? [] as $id => $message)
                    <li>
                        <article class="chatbox-message">
                            <header class="chatbox-message__header">
                                <address class="chatbox-message__address">
                                    <x-user_tag :user="$message['user']" :anon="false" />
                                </address>
                                <time
                                    class="chatbox-message__time"
                                    datetime="{{ $message['created_at'] }}"
                                    title="{{ $message['created_at'] }}"
                                    pubdate
                                >
                                    {{ \Carbon\Carbon::createFromTimestampUTC(strtotime($message['created_at']))->diffForHumans() }}
                                </time>
                            </header>
                            <aside class="chatbox-message__aside">
                                <figure class="chatbox-message__figure">
                                    <a
                                        href="{{ route('users.show', ['user' => $message['user']['id']]) }}"
                                        class="chatbox-message__avatar-link"
                                    >
                                        <img
                                            class="chatbox-message__avatar"
                                            src="{{ url($message['user']['image'] ? '/files/img/'.$message['user']['image'] : '/img/profile.png') }}"
                                            style="border: 1px solid {{ $message['user']['chat_status']['color'] }}"
                                            alt="{{ $message['user']['chat_status']['name'] }}"
                                        />
                                    </a>
                                </figure>
                            </aside>
                            <menu class="chatbox-message__menu">
                                <li class="chatbox-message__menu-item">
                                    <button
                                        class="chatbox-message__delete-button"
                                        wire:click.prevent="destroy({{ $id }})"
                                        title="Delete message"
                                    >
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                    </button>
                                </li>
                            </menu>
                            <section class="chatbox-message__content">
                                @joypixels((new \App\Helpers\Linkify())->linky((new \App\Helpers\Bbcode())->parse($message['message'])))
                            </section>
                        </article>
                    </li>
                @endforeach
            </ul>
        </div>
        <section class="chatroom__users" x-show="userMenuOpen">
            <h2 class="chatroom-users__heading">{{ __('common.users') }}</h2>
            <ul class="chatroom-users__list">
                @foreach($users ?? [] as $user)
                    <li class="chatroom-users__list-item">
                        <x-user_tag :user="$user" :anon="false" />
                        @if ($user['id'] !== auth()->id())
                            <menu class="chatroom-users__buttons">
                                <li>
                                    <button
                                        target="_blank"
                                        class="chatroom-users__button"
                                        title="Gift user bon (/gift <username> <amount> <message>)"
                                        x-on:click="$refs.chatboxInput.value = '/msg {{ $user['username'] }}'"
                                    >
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-gift"
                                        ></i>
                                    </button>
                                </li>
                                <li>
                                    <button
                                        class="chatroom-users__button"
                                        wire:click="createNewChat('{{ $user['username'] }}')"
                                        title="Send chat PM (/msg <username> <message>)"
                                    >
                                        <i
                                            class="{{ config('other.font-awesome') }} fa-envelope"
                                        ></i>
                                    </button>
                                </li>
                            </menu>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
        <form class="form chatroom__new-message">
            <p class="form__group">
                <textarea
                    id="chatbox__messages-create"
                    class="form__textarea"
                    name="message"
                    required=" "
                    wire:model.defer="message"
                    x-on:keydown.enter="
                        if ($event.keyCode === 13 && !$event.shiftKey) {
                            $event.preventDefault()
                            $wire.store();
                        }
                    "
                    x-ref="chatboxInput"
                ></textarea>
                <label class="form__label form__label--floating" for="chatbox__messages-create">
                    Write your message...
                </label>
            </p>
        </form>
    </div>
    <style>
    </style>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        getWsChannelName = (echo) => {
            if (echo.room_id !== null) {
                return `messages.room.${echo.room_id}`;
            } else if (echo.target_id !== null) {
                return `messages.pm.${[echo.user_id, echo.target_id].sort().join('-')}`;
            }
        }
        listenToChatbox = (echo) => {
            window.Echo
                .join(getWsChannelName(echo))
                .here((users) => Livewire.emit('updateChatbox', {
                    type: 'USER_LIST',
                    users: users
                }))
                .joining((user) => Livewire.emit('updateChatbox', {
                    type: 'USER_JOIN',
                    user: user
                }))
                .leaving((user) => Livewire.emit('updateChatbox', {
                    type: 'USER_LEAVE',
                    user: user
                }))
                .listen('CreateMessage', (e) => {
                    console.log(e);
                    Livewire.emit('updateChatbox', {
                        type: 'MESSAGE_CREATE',
                        message: e.message
                    });
                })
                .listen('DestroyMessage', (e) => {
                    console.log(e);
                    Livewire.emit('updateChatbox', {
                        type: 'MESSAGE_DESTROY',
                        message: e.message
                    });
                });
        }
        listenForNewChats = () => {
            window.Echo
                .private(`messages.new-chat.${@this.echo.user.id}`)
                .listen('NewChat', (e) => {
                    Livewire.emit('newChat', {
                        userId: e.userId,
                    });
                    console.log('Received new pm');
                })
        }
        document.addEventListener('livewire:load', function () {
            listenToChatbox(@this.echo);
            listenForNewChats(@this.echo);
        });
        window.addEventListener("updatedEchoId", (e) => {
            console.log(e);
            if (e.detail) {
                if (e.detail.previous) {
                    window.Echo.leave(getWsChannelName(e.detail.previous));
                }

                listenToChatbox(e.detail.new)
            }
        });
    </script>
</section>