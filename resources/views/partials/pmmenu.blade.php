<li class="{{ Route::is('users.received_messages.index') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('users.received_messages.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('users.received_messages.index', ['user' => $user]) }}"
    >
        {{ __('pm.inbox') }}
    </a>
</li>
<li class="{{ Route::is('users.sent_messages.index') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('users.sent_messages.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('users.sent_messages.index', ['user' => $user]) }}"
    >
        {{ __('pm.outbox') }}
    </a>
</li>
<li class="{{ Route::is('users.sent_messages.create') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('users.sent_messages.create') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('users.sent_messages.create', ['user' => $user]) }}"
    >
        {{ __('pm.new') }}
    </a>
</li>
