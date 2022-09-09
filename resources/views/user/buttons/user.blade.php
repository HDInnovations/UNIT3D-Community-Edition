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
            @if(auth()->user()->hidden)
                <form
                    method="POST"
                    action="{{ route('user_visible', ['username' => $user->username]) }}"
                    style="display: contents;"
                >
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.become-visible') }}
                    </button>
                </form>
            @else
                <form
                    method="POST"
                    action="{{ route('user_hidden', ['username' => $user->username]) }}"
                    style="display: contents;"
                >
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.become-hidden') }}
                    </button>
                </form>
            @endif
            @if(auth()->user()->private_profile)
                <form
                    method="POST"
                    action="{{ route('user_public', ['username' => $user->username]) }}"
                    style="display: contents;"
                >
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.go-public') }}
                    </button>
                </form>
            @else
                <form
                    method="POST"
                    action="{{ route('user_private', ['username' => $user->username]) }}"
                    style="display: contents;">
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.go-private') }}
                    </button>
                </form>
            @endif
            @if(auth()->user()->block_notifications)
                <form
                    method="POST"
                    action="{{ route('notification_enable', ['username' => $user->username]) }}"
                    style="display: contents;"
                >
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.enable-notifications') }}
                    </button>
                </form>
            @else
                <form
                    method="POST"
                    action="{{ route('notification_disable', ['username' => $user->username]) }}"
                    style="display: contents;"
                    >
                    @csrf
                    <button type="submit" class="nav-tab__link">
                        {{ __('user.disable-notifications') }}
                    </button>
                </form>
            @endif
        @else
            <li class="nav-tabV2">
                @if (auth()->user()->isFollowing($user->id))
                    <form
                        action="{{ route('follow.destroy', ['username' => $user->username]) }}"
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
                        action="{{ route('follow.store', ['username' => $user->username]) }}"
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
            class="{{ Route::is('user_settings', 'user_security', 'user_privacy', 'user_notification') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
            href="{{ route('user_settings', ['username' => $user->username]) }}"
        >
            {{ __('user.settings') }}
        </a>
        <ul class="nav-tab-menu__items">
            <li class="{{ Route::is('user_settings') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('user_settings') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('user_settings', ['username' => $user->username]) }}"
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
            <li class="{{ Route::is('user_privacy') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('user_privacy') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('user_privacy', ['username' => $user->username]) }}"
                >
                    {{ __('user.privacy') }}
                </a>
            </li>
            <li class="{{ Route::is('user_notification') ? 'nav-tab--active' : 'nav-tavV2' }}">
                <a
                    class="{{ Route::is('user_notification') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                    href="{{ route('user_notification', ['username' => $user->username]) }}"
                >
                    {{ __('user.notification') }}
                </a>
            </li>
        </ul>
    </li>
@endif
@if ($isProfileOwner || $isModo || auth()->user()->isAllowed($user, 'forum', 'show_requested'))
    <li class="nav-tab-menu">
        @if ($isProfileOwner || $isModo)
            <a
                class="{{ Route::is('user_torrents', 'user_uploads', 'user_active', 'user_resurrections', 'user_requested') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                href="{{ route('user_torrents', ['username' => $user->username]) }}"
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
                <li class="{{ Route::is('user_torrents') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_torrents') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_torrents', ['username' => $user->username]) }}"
                    >
                        {{ __('torrent.history') }}
                    </a>
                </li>
                <li class="nav-tabV2">
                    <a class="nav-tab__link" href="{{ route('user_uploads', ['username' => $user->username]) }}">
                        {{ __('user.uploads') }}
                    </a>
                </li>
                <li class="{{ Route::is('user_active') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_active') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_active', ['username' => $user->username]) }}"
                    >
                        {{ __('user.active') }}
                    </a>
                </li>
                <li class="{{ Route::is('user_resurrections') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a 
                        class="{{ Route::is('user_resurrections') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_resurrections', ['username' => $user->username]) }}"
                    >
                        {{ __('user.resurrections') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, '', 'show_requested'))
                <li class="{{ Route::is('user_requested') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_requested') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_requested', ['username' => $user->username]) }}"
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
                    action="{{ route('flush_own_ghost_peers', ['username' => $user->username]) }}"
                    method="POST"
                    style="display: contents;"
                >
                    @csrf
                    <button class="nav-tab__link" type="submit">
                        {{ __('staff.flush-ghost-peers') }}
                    </button>
                </form>
                <li class="nav-tabV2">
                    <a download class="nav-tab__link" href="{{ route('download_history_torrents', ['username' => $user->username]) }}">
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
        <span class="{{ Route::is('achievements.*', 'user_topics', 'user_posts', 'user_followers') ? 'nav-tab--active__link' : 'nav-tab__link' }}">
            {{ __('forum.activity') }}
        </span>
        <ul class="nav-tab-menu__items">
            @if (auth()->user()->isAllowed($user, 'achievement', 'show_achievement'))
                <li class="{{ Route::is('achievements.show') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('achievements.show') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('achievements.show', ['username' => $user->username]) }}"
                    >
                        {{ __('user.achievements') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'forum', 'show_topic'))
                <li class="{{ Route::is('user_topics') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_topics') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_topics', ['username' => $user->username]) }}"
                    >
                        {{ __('user.topics') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'forum', 'show_post'))
                <li class="{{ Route::is('user_posts') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_posts') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_posts', ['username' => $user->username]) }}"
                    >
                        {{ __('user.posts') }}
                    </a>
                </li>
            @endif
            @if (auth()->user()->isAllowed($user, 'follower', 'show_follower'))
                <li class="{{ Route::is('user_followers') ? 'nav-tab--active' : 'nav-tavV2' }}">
                    <a
                        class="{{ Route::is('user_followers') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
                        href="{{ route('user_followers', ['username' => $user->username]) }}"
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
