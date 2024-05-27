<li class="{{ Route::is('staff.users.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
    <a
        class="{{ Route::is('staff.users.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('staff.users.index') }}"
    >
        {{ __('common.users') }}
    </a>
</li>
<li class="{{ Route::is('staff.bans.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
    <a
        class="{{ Route::is('staff.bans.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('staff.bans.index') }}"
    >
        {{ __('user.bans') }}
    </a>
</li>
<li class="{{ Route::is('staff.invites.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
    <a
        class="{{ Route::is('staff.invites.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('staff.invites.index') }}"
    >
        {{ __('user.invites') }}
    </a>
</li>
<li class="{{ Route::is('staff.notes.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
    <a
        class="{{ Route::is('staff.notes.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('staff.notes.index') }}"
    >
        {{ __('staff.notes') }}
    </a>
</li>
<li class="{{ Route::is('staff.peers.index') ? 'nav-tab--active' : 'nav-tavV2' }}">
    <a
        class="{{ Route::is('staff.peers.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('staff.peers.index') }}"
    >
        Peers
    </a>
</li>
