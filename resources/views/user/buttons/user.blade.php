@php
    $isModo = auth()->user()->group->is_modo;
    $isProfileOwner = auth()->user()->id === $user->id
@endphp

<li class="nav-tab-menu">
    <a
        class="{{ Route::is('users.show', 'user_edit_profile_form') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('users.show', ['username' => $user->username]) }}"
    >
        {{ __('user.profile') }}
    </a>
    <ul class="nav-tab-menu__items">
        <li class="{{ Route::is('users.show') ? 'nav-tab--active' : 'nav-tavV2' }}">
            <a
                class="{{ Route::is('users.show') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                href="{{ route('users.show', ['username' => $user->username]) }}"
            >
                {{ __('user.profile') }}
            </a>
        </li>
        @if ($isProfileOwner)
            <li class="{{ Route::is('user_edit_profile_form') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('user_edit_profile_form') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('user_edit_profile_form', ['username' => $user->username]) }}"
                >
                    {{ __('user.edit-profile') }}
                </a>
            </li>
        @else
            <li class="nav-tabV2">
                @if ($user->followers()->where('users.id', '=', auth()->user()->id)->exists())
                    <form
                        action="{{ route('users.followers.destroy', ['user' => $user]) }}"
                        method="POST"
                        style="display: contents;"
                    >
                        @csrf
                        @method('DELETE')
                        <button class="nav-tab__link" type="submit" id="delete-follow-{{ $user->target_id }}">
                            {{ __('user.unfollow') }}
                        </button>
                    </form>
                @else
                    <form
                        action="{{ route('users.followers.store', ['user' => $user]) }}"
                        method="POST"
                        style="display: contents;"
                    >
                        @csrf
                        <button class="nav-tab__link" type="submit" id="follow-user-{{ $user->id }}">
                            {{ __('user.follow') }}
                        </button>
                    </form>
                @endif
            </li>
            <li class="nav-tabV2">
                <button class="nav-tab__link" data-toggle="modal" data-target="#modal_user_report">
                    {{ __('user.report') }}
                </button>
            </li>
        @endif
    </ul>
</li>
@if ($isProfileOwner)
    <li class="nav-tab-menu">
        <a
            class="{{ Route::is('users.general_settings.edit', 'user_security', 'user_privacy', 'user_notification') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
            href="{{ route('users.general_settings.edit', ['user' => $user]) }}"
        >
            {{ __('user.settings') }}
        </a>
        <ul class="nav-tab-menu__items">
            <li class="{{ Route::is('users.general_settings.edit') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('users.general_settings.edit') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('users.general_settings.edit', ['user' => $user]) }}"
                >
                    {{ __('user.general') }}
                </a>
            </li>
            <li class="{{ Route::is('user_security') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('user_security') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('user_security', ['username' => $user->username]) }}"
                >
                    {{ __('user.security') }}
                </a>
            </li>
            <li class="{{ Route::is('users.privacy_settings.edit') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('users.privacy_settings.edit') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('users.privacy_settings.edit', ['user' => $user]) }}"
                >
                    {{ __('user.privacy') }}
                </a>
            </li>
            <li class="{{ Route::is('users.notification_settings.edit') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('users.notification_settings.edit') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('users.notification_settings.edit', ['user' => $user]) }}"
                >
                    {{ __('user.notification') }}
                </a>
            </li>
        </ul>
    </li>
@endif
@if ($isProfileOwner || $isModo)
    <li class="nav-tab-menu">
        @if ($isProfileOwner || $isModo)
            <a
                class="{{ Route::is('users.history.index', 'users.torrents.index', 'users.peers.index', 'users.resurrections.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                href="{{ route('users.history.index', ['user' => $user]) }}"
            >
                {{ __('torrent.torrents') }}
            </a>
        @else
            <span class="nav-tab__link">
                {{ __('torrent.torrents') }}
            </span>
        @endif
        <ul class="nav-tab-menu__items">
            @if ($isProfileOwner || $isModo)
                <li class="{{ Route::is('users.history.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.history.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.history.index', ['user' => $user]) }}"
                    >
                        {{ __('torrent.history') }}
                    </a>
                </li>
                <li class="nav-tabV2">
                    <a class="nav-tab__link" href="{{ route('users.torrents.index', ['user' => $user]) }}">
                        {{ __('user.uploads') }}
                    </a>
                </li>
                <li class="{{ Route::is('users.peers.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.peers.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.peers.index', ['user' => $user]) }}"
                    >
                        {{ __('user.active') }}
                    </a>
                </li>
                <li class="{{ Route::is('users.resurrections.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a 
                        class="{{ Route::is('users.resurrections.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.resurrections.index', ['user' => $user]) }}"
                    >
                        {{ __('user.resurrections') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, '', 'show_requested'))
                <li class="nav-tavV2">
                    <a
                        class="nav-tab__link"
                        href="{{ route('requests.index', ['requestor' => $user->username]) }}"
                    >
                        {{ __('user.requested') }}
                    </a>
                </li>
            @endif
            @if($isProfileOwner)
                <li class="nav-tabV2">
                    <a class="nav-tab__link" href="{{ route('torrents', ['bookmarked' => '1']) }}">
                        {{ __('user.bookmarks') }}
                    </a>
                </li>
                <form
                    action="{{ route('users.peers.mass_destroy', ['user' => $user]) }}"
                    method="POST"
                    style="display: contents;"
                >
                    @csrf()
                    @method('DELETE')
                    <button class="nav-tab__link" type="submit">
                        {{ __('staff.flush-ghost-peers') }}
                    </button>
                </form>
                <li class="nav-tabV2">
                    <a download class="nav-tab__link" href="{{ route('users.torrent_zip.show', ['user' => $user]) }}">
                        {{ __('torrent.download-all') }}
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if (
    auth()->user()->isAllowed($user, 'achievement', 'show_achievement')
    || auth()->user()->isAllowed($user, 'forum', 'show_topic')
    || auth()->user()->isAllowed($user, 'forum', 'show_post')
    || auth()->user()->isAllowed($user, 'follower', 'show_follower')
)
    <li class="nav-tab-menu">
        <span class="{{ Route::is('users.achievements.*', 'users.topics.index', 'users.posts.index', 'users.followers.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}">
            {{ __('forum.activity') }}
        </span>
        <ul class="nav-tab-menu__items">
            @if (auth()->user()->isAllowed($user, 'achievement', 'show_achievement'))
                <li class="{{ Route::is('users.achievements.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.achievements.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.achievements.index', ['user' => $user]) }}"
                    >
                        {{ __('user.achievements') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'forum', 'show_topic'))
                <li class="{{ Route::is('users.topics.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.topics.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.topics.index', ['user' => $user]) }}"
                    >
                        {{ __('user.topics') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'forum', 'show_post'))
                <li class="{{ Route::is('users.posts.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.posts.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.posts.index', ['user' => $user]) }}"
                    >
                        {{ __('user.posts') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'follower', 'show_follower'))
                <li class="{{ Route::is('users.followers.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('users.followers.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('users.followers.index', ['user' => $user]) }}"
                    >
                        {{ __('user.followers') }}
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if ($isProfileOwner || $isModo)
    <li class="nav-tab-menu">
        <a
            class="{{ Route::is('earnings.index', 'transactions.create', 'gifts.index', 'gifts.create', 'tips.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
            href="{{ route('earnings.index', ['username' => $user->username]) }}"
        >
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
        <ul class="nav-tab-menu__items">
            <li class="{{ Route::is('earnings.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('earnings.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('earnings.index', ['username' => $user->username]) }}"
                >
                    {{ __('bon.earnings') }}
                </a>
            </li>
            @if ($isProfileOwner)
                <li class="{{ Route::is('transactions.create') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('transactions.create') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('transactions.create', ['username' => $user->username]) }}"
                    >
                        {{ __('bon.store') }}
                    </a>
                </li>
            @endif
            <li class="{{ Route::is('gifts.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('gifts.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('gifts.index', ['username' => $user->username]) }}"
                >
                    {{ __('bon.gifts') }}
                </a>
            </li>
            <li class="{{ Route::is('tips.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('tips.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('tips.index', ['username' => $user->username]) }}"
                >
                    {{ __('bon.tips') }}
                </a>
            </li>
        </ul>
    </li>
@endif
@if ($isProfileOwner || $isModo)
    <li class="nav-tab-menu">
        <span class="{{ Route::is('wishes.*', 'seedboxes.*', 'invites.*') ? 'nav-tab--active__link' : 'nav-tab__link' }}">
            {{ __('common.other') }}
        </span>
        <ul class="nav-tab-menu__items">
            @if ($isProfileOwner || $isModo)
                <li class="{{ Route::is('wishes.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('wishes.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('wishes.index', ['username' => $user->username]) }}"
                    >
                        {{ __('user.wishlist') }}
                    </a>
                </li>
                <li class="{{ Route::is('seedboxes.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('seedboxes.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('seedboxes.index', ['username' => $user->username]) }}"
                    >
                        {{ __('user.seedboxes') }}
                    </a>
                </li>
                <li class="{{ Route::is('invites.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('invites.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('invites.index', ['username' => $user->username]) }}"
                    >
                        {{ __('user.invites') }}
                    </a>
                </li>
            @endif
            @if ($isProfileOwner)
                <li class="{{ Route::is('invites.create') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('invites.create') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('invites.create') }}"
                    >
                        {{ __('user.send-invite') }}
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
