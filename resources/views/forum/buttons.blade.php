<li class="{{ Route::is('forum_latest_topics') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('forum_latest_topics') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('forum_latest_topics') }}"
    >
        {{ __('common.latest-topics') }}
    </a>
</li>
<li class="{{ Route::is('forum_latest_posts') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('forum_latest_posts') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('forum_latest_posts') }}"
    >
        {{ __('common.latest-posts') }}
    </a>
</li>
<li class="{{ Route::is('forum_subscriptions') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('forum_subscriptions') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('forum_subscriptions') }}"
    >
        {{ __('common.subscriptions') }}
    </a>
</li>
<li class="{{ Route::is('forum_search_form') ? 'nav-tab--active' : 'nav-tabV2' }}">
    <a
        class="{{ Route::is('forum_search_form') ? 'nav-tab--active__link' : 'nav-tab__link' }}"
        href="{{ route('forum_search_form') }}"
    >
        {{ __('common.search') }}
    </a>
</li>
