<li class="{{ Route::is('uploaded') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('uploaded') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('uploaded') }}"
    >
        {{ __('user.top-uploaders-data') }}
    </a>
</li>
<li class="{{ Route::is('seeders') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('seeders') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('seeders') }}"
    >
        {{ __('user.top-seeders') }}
    </a>
</li>
<li class="{{ Route::is('leechers') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('leechers') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('leechers') }}"
    >
        {{ __('user.top-leechers') }}
    </a>
</li>
<li class="{{ Route::is('uploaders') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('uploaders') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('uploaders') }}"
    >
        {{ __('user.top-uploaders-count') }}
    </a>
</li>
<li class="{{ Route::is('bankers') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('bankers') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('bankers') }}"
    >
        {{ __('user.top-bankers') }}
    </a>
</li>
