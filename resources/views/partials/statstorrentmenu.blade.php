<li class="{{ Route::is('seeded') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('seeded') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('seeded') }}"
    >
        {{ __('user.top-seeded') }}
    </a>
</li>
<li class="{{ Route::is('leeched') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('leeched') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('leeched') }}"
    >
        {{ __('user.top-leeched') }}
    </a>
</li>
<li class="{{ Route::is('completed') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('completed') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('completed') }}"
    >
        {{ __('user.top-completed') }}
    </a>
</li>
<li class="{{ Route::is('dying') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('dying') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('dying') }}"
    >
        {{ __('user.top-dying') }}
    </a>
</li>
<li class="{{ Route::is('dead') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('dead') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('dead') }}"
    >
        {{ __('user.top-dead') }}
    </a>
</li>
