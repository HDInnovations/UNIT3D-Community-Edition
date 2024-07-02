<section wire:ignore class="panelV2 chatbox" x-data="{ ...chatbox() }" x-init="listen">
    <header class="panel__header">
        <h2 class="panel__heading">Chatbox</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="user-list"
                        class="form__checkbox"
                        required
                        type="checkbox"
                        x-model="userMenuOpen"
                    />
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
                        wire:model="user.chatroom_id"
                    >
                        @foreach ($this->rooms ?? [] as $id => $room)
                            <option
                                value="{{ $id }}"
                                @selected($id === auth()->user()->chatroom_id)
                            >
                                {{ $room['name'] }}
                            </option>
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
                        wire:model="user.chat_status_id"
                    >
                        @foreach ($this->statuses ?? [] as $id => $status)
                            <option
                                value="{{ $id }}"
                                @selected($id === auth()->user()->chat_status_id)
                            >
                                {{ $status['name'] }}
                            </option>
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
        <template x-for="echo in echoes" :key="echo.id">
            <li
                class="panel__tab chatbox__tab"
                role="tab"
                x-bind:class="currentEcho.id == echo.id && 'panel__tab--active'"
                x-cloak
                x-on:mousedown="
                    if ($event && ($event.which === 2 || $event.buttons === 4)) {
                        @this.destroyEcho(echo.id);
                        delete echoes[echo.id];
                    } else {
                        currentEcho = echo;
                    }
                "
            >
                <span
                    x-text="
                        if (echo.room_id !== null) {
                            return rooms[echo.room_id]?.name ?? 'Loading...';
                        } else if (echo.target_id !== null) {
                            return users[echo.target_id]?.username ?? 'Loading...';
                        } else {
                            return 'Unknown';
                        }
                    "
                ></span>
                <button
                    x-show="echo.id == currentEcho.id"
                    class="chatbox__tab-delete-button"
                    x-on:click.prevent="
                        @this.destroyEcho(echo.id);
                        delete echoes[echo.id];
                    "
                >
                    <i
                        class="{{ config('other.font-awesome') }} fa-times chatbox__tab-delete-icon"
                    ></i>
                </button>
            </li>
        </template>
    </menu>
    <div class="chatbox__chatroom">
        <div class="chatroom__messages--wrapper">
            <ul class="chatroom__messages">
                <template x-for="message in messages" :key="message.id">
                    <li>
                        <article class="chatbox-message">
                            <header class="chatbox-message__header">
                                <address
                                    class="chatbox-message__address user-tag"
                                    x-bind:style="users[message.user_id] && { backgroundImage: groups[users[message.user_id].group_id]?.effect }"
                                >
                                    <a
                                        class="user-tag__link"
                                        x-bind:class="groups[users[message.user_id].group_id].icon"
                                        x-bind:href="`/users/${users[message.user_id].username}`"
                                        x-bind:style="{ color: groups[users[message.user_id].group_id].color }"
                                        x-bind:title="groups[users[message.user_id].group_id].name"
                                        x-text="users[message.user_id].username"
                                    ></a>
                                </address>
                                <time
                                    class="chatbox-message__time"
                                    x-bind:datetime="message.created_at"
                                    x-bind:title="message.created_at"
                                    pubdate
                                    x-text="message.created_at"
                                ></time>
                            </header>
                            <aside class="chatbox-message__aside">
                                <figure class="chatbox-message__figure">
                                    <a
                                        x-bind:href="users[message.user_id]?.username ? `/users/${users[message.user_id].username}` : '#'"
                                        class="chatbox-message__avatar-link"
                                    >
                                        <img
                                            class="chatbox-message__avatar"
                                            x-bind:src="
                                                users[message.user_id]?.image === null
                                                    ? '/img/profile.png'
                                                    : `/files/img/${users[message.user_id].image}`
                                            "
                                            x-bind:style="{ borderColor: statuses[users[message.user_id].chat_status_id].color }"
                                            x-bind:alt="statuses[users[message.user_id].chat_status_id].name"
                                        />
                                    </a>
                                </figure>
                            </aside>
                            <menu class="chatbox-message__menu">
                                <li class="chatbox-message__menu-item">
                                    <button
                                        class="chatbox-message__delete-button"
                                        title="Delete message"
                                        x-on:click.prevent="$wire.destroy(message.id)"
                                    >
                                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                    </button>
                                </li>
                            </menu>
                            <section
                                class="chatbox-message__content"
                                x-text="message.message"
                            ></section>
                        </article>
                    </li>
                </template>
                <template
                    x-if="currentEcho.target_id !== null && Object.keys(messages()).length === 0"
                >
                    <li>You have no conversation history with this user. Send them a message!</li>
                </template>
                <template
                    x-if="currentEcho.room_id !== null && Object.keys(messages()).length === 0"
                >
                    <li>This channel has no conversation history. Send a message!</li>
                </template>
            </ul>
        </div>
        <template x-if="userMenuOpen">
            <section class="chatroom__users">
                <h2 class="chatroom-users__heading">{{ __('common.users') }}</h2>
                <ul class="chatroom-users__list">
                    <template x-for="id in subscribedUserIds" :key="id">
                        <li class="chatroom-users__list-item">
                            <span
                                class="user-tag"
                                x-bind:style="users[id] !== null && { backgroundImage: groups[users[id].group_id].effect }"
                            >
                                <a
                                    class="user-tag__link"
                                    x-bind:class="groups[users[id].group_id].icon"
                                    x-bind:href="`/users/${users[id].username}`"
                                    x-bind:style="{ color: groups[users[id].group_id].color }"
                                    x-bind:title="groups[users[id].group_id].name"
                                    x-text="users[id].username"
                                ></a>
                            </span>
                            <template x-if="id !== userId">
                                <menu class="chatroom-users__buttons">
                                    <li>
                                        <button
                                            target="_blank"
                                            class="chatroom-users__button"
                                            title="Gift user bon (/gift <username> <amount> <message>)"
                                            x-on:click="$refs.chatboxInput.value = `/gift ${users[id].username}`"
                                        >
                                            <i
                                                class="{{ config('other.font-awesome') }} fa-gift"
                                            ></i>
                                        </button>
                                    </li>
                                    <li>
                                        <button
                                            class="chatroom-users__button"
                                            title="Send chat PM (/msg <username> <message>)"
                                            x-on:click="
                                                let echo = await @this.createEcho(users[id].username);
                                                echoes[echo.id] = echo;
                                                currentEcho = echo;
                                            "
                                        >
                                            <i
                                                class="{{ config('other.font-awesome') }} fa-envelope"
                                            ></i>
                                        </button>
                                    </li>
                                </menu>
                            </template>
                        </li>
                    </template>
                </ul>
            </section>
        </template>
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
                            $event.preventDefault();
                            @this.store();
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
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        function chatbox() {
            return {
                currentEcho: @entangle('echo'),
                userId: @js(auth()->id()),
                userMenuOpen: true,
                echoes: @js($this->echoes),
                users: @js($this->users),
                channels: @js($this->msgs),
                rooms: @js($this->rooms),
                groups: @js($this->groups),
                statuses: @js($this->statuses),
                audibles: @js($this->audibles),
                subscriptions: {},
                getWsChannelName(echo) {
                    if (echo.room_id !== null) {
                        return `messages.room.${echo.room_id}`;
                    } else if (echo.user_id !== null && echo.target_id !== null) {
                        return `messages.pm.${[echo.user_id, echo.target_id].sort().join('-')}`;
                    }
                },
                messages() {
                    if (
                        typeof this.channels[this.getWsChannelName(this.currentEcho)] ===
                        'undefined'
                    ) {
                        return {};
                    }

                    return this.channels[this.getWsChannelName(this.currentEcho)]?.messages;
                },
                subscribedUserIds() {
                    if (
                        typeof this.subscriptions[this.getWsChannelName(this.currentEcho)] ===
                        'undefined'
                    ) {
                        return {};
                    }

                    return this.subscriptions[this.getWsChannelName(this.currentEcho)];
                },
                listenToEcho(echo) {
                    let channelName = this.getWsChannelName(echo);
                    window.Echo.join(channelName)
                        .here((users) => {
                            this.subscriptions[channelName] = [];

                            users.forEach((user) => {
                                this.users[user.id] = user;
                                this.subscriptions[channelName].push(user.id);
                            });
                        })
                        .joining((user) => {
                            if (typeof this.subscriptions[channelName] === 'undefined') {
                                this.subscriptions[channelName] = [];
                            }
                            this.users[user.id] = user;
                            this.subscriptions[channelName].push(user.id);
                        })
                        .leaving((user) => {
                            this.subscriptions[channelName] = this.subscriptions[
                                channelName
                            ].filter((userId) => userId !== user.id);
                        })
                        .listen('MessageCreated', (e) => {
                            if (
                                typeof this.channels[channelName] === 'undefined' ||
                                this.channels[channelName]?.messages?.constructor === Array
                            ) {
                                this.channels[channelName] = [];
                                this.channels[channelName].messages = {};
                            }

                            this.channels[channelName].messages[e.message.id] = e.message;

                            if (
                                echo.id !== this.currentEcho.id &&
                                e.message.user_id !== this.userId
                            ) {
                                Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                }).fire({
                                    icon: 'info',
                                    title: (() => {
                                        if (e.message.chatroom_id !== null) {
                                            return `${this.users[e.message.user_id].username} (${this.rooms[e.message.chatroom_id].name})`;
                                        }

                                        if (e.message.user_id !== null) {
                                            return this.users[e.message.user_id].username;
                                        }

                                        return 'New Message';
                                    })(),
                                    text: (() => {
                                        if (e.message.chatroom_id !== null) {
                                            return `${this.users[e.message.user_id].username}: ${e.message.message}`;
                                        }
                                        if (e.message.user_id !== null) {
                                            return e.message.message;
                                        }
                                    })(),
                                });
                            }
                        })
                        .listen('MessageDeleted', (e) => {
                            delete this.channels[channelName].messages[e.messageId];
                        })
                        .listen('UserEdited', (e) => {
                            if (e.statusId !== null) {
                                this.users[e.userId].chat_status_id = e.chatStatusId;
                            }

                            if (e.image !== null) {
                                this.users[e.userId].image = e.image;
                            }

                            if (e.username !== null) {
                                this.users[e.userId].username = e.username;
                            }

                            if (e.groupId !== null) {
                                this.users[e.userId].group_id = e.groupId;
                            }
                        });
                },
                listenForNewEchoes() {
                    window.Echo.private(`echo.created.${this.userId}`).listen(
                        'EchoCreated',
                        (e) => {
                            this.echoes[e.echo.id] = e.echo;
                            this.listenToEcho(e.echo);
                        },
                    );
                },
                listen() {
                    for (let echo of Object.values(this.echoes)) {
                        this.listenToEcho(echo);
                    }
                    this.listenForNewEchoes();
                },
            };
        }
    </script>
</section>
