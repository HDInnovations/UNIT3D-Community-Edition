<header id="hoe-header" hoe-color-type="header-bg5" hoe-lpanel-effect="shrink" class="hoe-minimized-lpanel">
    <div class="hoe-left-header" hoe-position-type="fixed">
        <a href="{{ route('home') }}">
            <div class="banner"><i class="{{ config('other.font-awesome') }} fa-rocket" style="display: inline;"></i>
                <span>{{ config('other.title') }}</span></div>
        </a>
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
    </div>
    <div class="hoe-right-header" hoe-position-type="relative" hoe-color-type="header-bg5">
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
        <ul class="left-navbar">
            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                @php $pm = DB::table('private_messages')->where('receiver_id', '=', auth()->user()->id)->where('read', '=', '0')->count(); @endphp
                <a href="{{ route('inbox', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}"
                   class="dropdown-toggle icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-envelope text-blue"></i>
                    @if($pm > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
            </li>

            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                <a href="{{ route('get_notifications') }}" class="dropdown-toggle icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
            </li>

            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                <a href="{{ route('achievements') }}" class="dropdown-toggle icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-trophy text-gold"></i>
                </a>
            </li>

            @if(auth()->user()->group->is_modo)
                <li class="dropdown hoe-rheader-submenu message-notification left-min-65">
                    <a href="#" class="dropdown-toggle icon-circle" data-toggle="dropdown" aria-expanded="false">
                        <i class="{{ config('other.font-awesome') }} fa-tasks text-red"></i>
                        @php $modder = DB::table('torrents')->where('status', '=', '0')->count(); @endphp
                        @if($modder > 0)
                            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                        @endif
                    </a>
                    <ul class="dropdown-menu ">
                        <li class="hoe-submenu-label">
                            <h3> {{ trans('staff.you-have') }} <span
                                        class="bold">{{ $modder }}  </span>{{ strtolower(trans('common.pending-torrents')) }}
                                <a href="{{ route('moderation') }}">{{ trans('common.view-all') }}</a></h3>
                        </li>
                        <li>
                            @php $pending = DB::table('torrents')->where('status', '=', '0')->get(); @endphp
                            @foreach($pending as $p)
                                <a href="#" class="clearfix">
                                    <span class="notification-title"> {{ $p->name }} </span>
                                    <span class="notification-ago-1 ">{{ $p->created_at }}</span>
                                    <p class="notification-message">{{ trans('staff.please-moderate') }}</p>
                                </a>
                            @endforeach
                        </li>
                    </ul>
                </li>
            @endif

            <li>
                <form class="hoe-searchbar" role="form" method="GET"
                      action="{{ action('TorrentController@torrents') }}">
                    @csrf
                    <input name="search" type="text" id="name" placeholder="{{ trans('common.quick-search') }}"
                           class="form-control">
                    <span class="search-icon"><i class="{{ config('other.font-awesome') }} fa-search"></i></span>
                </form>
            </li>
        </ul>

        <ul class="right-navbar">
            <li class="dropdown hoe-rheader-submenu hoe-header-profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>
                <img src="{{ url('img/flags/'.strtolower(auth()->user()->locale).'.png') }}" class="img-circle"/>
            </span>
                    <span><i class=" {{ config('other.font-awesome') }} fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu ">
                    @foreach (App\Language::allowed() as $code => $name)
                        <li class="{{ config('language.flags.li_class') }}">
                            <a href="{{ route('back', ['local' => $code]) }}">
                                <img src="{{ url('img/flags/'.strtolower($code).'.png') }}" alt="{{ $name }}"
                                     width="{{ config('language.flags.width') }}"/>
                                &nbsp;{{ $name }} @if(auth()->user()->locale == $code)<span
                                        class="text-orange text-bold">({{ trans('common.active') }}!)</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="dropdown hoe-rheader-submenu hoe-header-profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span>
            @if(auth()->user()->image != null)
                  <img src="{{ url('files/img/' . auth()->user()->image) }}" alt="{{ auth()->user()->username }}"
                       class="img-circle">
              @else
                  <img src="{{ url('img/profile.png') }}" alt="{{ auth()->user()->username }}" class="img-circle">
              @endif
          </span>
                    <span><i class=" {{ config('other.font-awesome') }} fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu ">
                    <li>
                        <a href="{{ route('profile', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-user"></i> {{ trans('user.my-profile') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_settings', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-cogs"></i> {{ trans('user.account-settings') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wishlist', ['id' => auth()->user()->id]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-clipboard-list"></i> {{ trans('user.my-wishlist') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="{{ config('other.font-awesome') }} fa-sign-out"></i> {{ trans('auth.logout') }}
                        </a>

                        <form id="logout-form"
                              action="{{ route('logout') }}"
                              method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        </li>
    </div>
</header>
