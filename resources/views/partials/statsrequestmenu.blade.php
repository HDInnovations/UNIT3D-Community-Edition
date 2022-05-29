<li class="{{ Route::is('bountied') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('bountied') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('bountied') }}"
    >
        {{ __('user.top-bountied') }} ({{ __('bon.bon') }})
    </a>
</li>
