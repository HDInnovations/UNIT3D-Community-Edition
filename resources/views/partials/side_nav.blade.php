<aside id="hoe-left-panel" hoe-position-type="fixed" hoe-position-type="absolute">
    <ul class="nav panel-list">
        <li class="nav-level">{{ trans('common.navigation') }}</li>
        <li>
            <a href="{{ route('home') }}">
                <i class="livicon" data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.home') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            @if(auth()->user()->torrent_layout == 1)
                <a href="{{ route('grouping_categories') }}">
            @elseif(auth()->user()->torrent_layout == 2)
                <a href="{{ route('cards') }}">
            @else
                <a href="{{ route('torrents') }}">
            @endif
                <i class="livicon" data-name="medal" data-size="18" data-c="#00bc8c" data-hc="#00bc8c"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('torrent.torrents') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('forum_index') }}">
                <i class="livicon" data-name="doc-portrait" data-c="#5bc0de" data-hc="#5bc0de" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('forum.forums') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('upload') }}">
                <i class="livicon" data-name="upload" data-c="#F89A14" data-hc="#F89A14" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.upload') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('requests') }}">
                <i class="livicon" data-name="folder-add" data-c="#f1c40f" data-hc="#f1c40f" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('request.requests') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('graveyard') }}">
                <i class="livicon" data-name="ghost" data-c="#7289DA" data-hc="#7289DA" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('graveyard.graveyard') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bookmarks') }}">
                <i class="livicon" data-name="bookmark" data-c="silver" data-hc="silver" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('torrent.bookmarks') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('gallery') }}">
                <i class="livicon" data-name="image" data-c="teal" data-hc="teal" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">Gallery</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('stats') }}">
                <i class="livicon" data-name="barchart" data-c="pink" data-hc="pink" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.extra-stats') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('polls') }}">
                <i class="livicon" data-name="piechart" data-c="green" data-hc="green" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('poll.polls') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bonus') }}">
                <i class="livicon" data-name="star-full" data-c="#BF55EC" data-hc="#BF55EC" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('bon.bon') }} {{ trans('bon.store') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('inbox', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">
                <i class="livicon" data-name="mail" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                <span class="menu-text">{{ trans('pm.inbox') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('bug') }}">
                <i class="livicon" data-name="bug" data-c="#E74C3C" data-hc="#E74C3C" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.bug') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}/p/rules.1">
                <i class="livicon" data-name="info" data-c="#ecf0f1" data-hc="#ecf0f1" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.rules') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}/p/faq.3">
                <i class="livicon" data-name="question" data-c="#ecf0f1" data-hc="#ecf0f1" data-size="18"
                   data-loop="true"></i>
                <span class="menu-text">{{ trans('common.faq') }}</span>
                <span class="selected"></span>
            </a>
        </li>
        {{--<li>
          <a href="{{ route('rss', ['passkey' => auth()->user()->passkey]) }}">
            <i class="livicon" data-name="rss" data-c="orange" data-hc="orange" data-size="18" data-loop="true"></i>
            <span class="menu-text">{{ trans('torrent.rss') }}</span>
            <span class="selected"></span>
          </a>
        </li>--}}
        @if(auth()->user()->group->is_modo)
            <li>
                <a href="{{ route('staff_dashboard') }}">
                    <i class="livicon" data-name="gears" data-c="#E74C3C" data-hc="#E74C3C" data-size="18"
                       data-loop="true"></i>
                    <span class="menu-text">{{ trans('staff.staff-dashboard') }}</span>
                    <span class="selected"></span>
                </a>
            </li>
        @endif
    </ul>
</aside>
