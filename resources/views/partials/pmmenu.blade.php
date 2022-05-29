<li class="{{ Route::is('inbox') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('inbox') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('inbox') }}"
    >
        {{ __('pm.inbox') }}
    </a>
</li>
<li class="{{ Route::is('outbox') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('outbox') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('outbox') }}"
    >
        {{ __('pm.outbox') }}
    </a>
</li>
<li class="{{ Route::is('create') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('create') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('create') }}"
    >
        {{ __('pm.new') }}
    </a>
</li>
