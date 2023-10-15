<li class="{{ Route::is('topics.index') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('topics.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('topics.index') }}"
    >
        {{ __('common.topics') }}
    </a>
</li>
<li class="{{ Route::is('posts.index') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('posts.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('posts.index') }}"
    >
        {{ __('common.posts') }}
    </a>
</li>
<li class="{{ Route::is('subscriptions.index') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('subscriptions.index') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('subscriptions.index') }}"
    >
        {{ __('common.subscriptions') }}
    </a>
</li>
