<aside id="hoe-left-panel" hoe-position-type="fixed" hoe-position-type="absolute">
    <ul class="nav panel-list">
        <li class="nav-level">{{ trans('common.navigation') }}</li>
        <li>
            <a href="{{ route('home') }}">
                <i class="{{ config('other.font-awesome') }} fa-home" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.home') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            @if (auth()->user()->torrent_layout == 1)
                <a href="{{ route('grouping_categories') }}">
            @elseif (auth()->user()->torrent_layout == 2)
                <a href="{{ route('cards') }}">
            @else
                <a href="{{ route('torrents') }}">
            @endif
                <i class="{{ config('other.font-awesome') }} fa-magnet" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('torrent.torrents') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('forum_index') }}">
                <i class="{{ config('other.font-awesome') }} fa-comments" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('forum.forums') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('upload') }}">
                <i class="{{ config('other.font-awesome') }} fa-upload" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.upload') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('requests') }}">
                <i class="{{ config('other.font-awesome') }} fa-copy" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('request.requests') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('graveyard') }}">
                <i class="fab fa-snapchat-ghost" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('graveyard.graveyard') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bookmarks') }}">
                <i class="{{ config('other.font-awesome') }} fa-bookmark" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('torrent.bookmarks') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('gallery') }}">
                <i class="{{ config('other.font-awesome') }} fa-images" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">Gallery</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('stats') }}">
                <i class="{{ config('other.font-awesome') }} fa-chart-bar" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.extra-stats') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('polls') }}">
                <i class="{{ config('other.font-awesome') }} fa-chart-pie" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('poll.polls') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bonus', ['username' => auth()->user()->username]) }}">
                <i class="{{ config('other.font-awesome') }} fa-shopping-cart" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('bon.bon') }} {{ trans('bon.store') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('inbox', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">
                <i class="{{ config('other.font-awesome') }} fa-envelope" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('pm.inbox') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bug') }}">
                <i class="{{ config('other.font-awesome') }} fa-bug" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.bug') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}/page/rules.1">
                <i class="{{ config('other.font-awesome') }} fa-info-square" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.rules') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}/page/faq.3">
                <i class="{{ config('other.font-awesome') }} fa-question-square" style=" font-size: 18px; color: #fff;"></i>
                <span class="menu-text">{{ trans('common.faq') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        {{--<li>
          <a href="{{ route('rss', ['passkey' => auth()->user()->passkey]) }}">
            <i class="{{ config('other.font-awesome') }} fa-rss" style=" font-size: 18px; color: #fff;">{{ trans('torrent.rss') }}</span>
            <span class="selected"></span>
          </a>
        </li>--}}
        @if (auth()->user()->group->is_modo)
            <li>
                <a href="{{ route('staff_dashboard') }}">
                    <i class="{{ config('other.font-awesome') }} fa-cogs" style=" font-size: 18px; color: #fff;"></i>
                    <span class="menu-text">{{ trans('staff.staff-dashboard') }}</span>
                    <span class="selected"></span>
                </a>
            </li>
        @endif
    </ul>
</aside>
