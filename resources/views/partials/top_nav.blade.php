<header id="hoe-header" hoe-color-type="header-bg5" hoe-lpanel-effect="shrink" class="hoe-minimized-lpanel">
    <div class="hoe-left-header" hoe-position-type="fixed">
        <a href="{{ route('home.index') }}">
            <div class="banner">
                <i class="fal fa-rocket" style="display: inline;"></i>
            </div>
        </a>
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
    </div>
    <div class="hoe-right-header" hoe-position-type="fixed" hoe-color-type="header-bg5">
        <span class="hoe-sidebar-toggle"><a href="#"></a></span>
        <ul class="left-navbar">
            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                @php $pm = DB::table('private_messages')->where('receiver_id', '=', auth()->user()->id)->where('read', '=', '0')->count(); @endphp
                <a href="{{ route('inbox') }}"
                   class="dropdown-toggle icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-envelope text-blue"></i>
                    @if ($pm > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
            </li>

            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                <a href="{{ route('notifications.index') }}" class="icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-bell"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                    @endif
                </a>
            </li>

            <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
                <a href="{{ route('achievements.index') }}" class="icon-circle">
                    <i class="{{ config('other.font-awesome') }} fa-trophy text-gold"></i>
                </a>
            </li>

            @if (auth()->user()->group->is_modo)
                <li class="dropdown hoe-rheader-submenu message-notification left-min-65">
                    <a href="{{ route('staff.moderation.index') }}" class="icon-circle">
                        <i class="{{ config('other.font-awesome') }} fa-tasks text-red"></i>
                        @php $modder = DB::table('torrents')->where('status', '=', '0')->count(); @endphp
                        @if ($modder > 0)
                            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
                        @endif
                    </a>
                </li>
            @endif
        </ul>

        <ul class="right-navbar">
            <li class="dropdown hoe-rheader-submenu hoe-header-profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>
                <img src="{{ url('img/flags/'.auth()->user()->locale).'.png' }}" alt="flag" class="img-circle {{ auth()->user()->locale }}"/>
            </span>
                    <span><i class=" {{ config('other.font-awesome') }} fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach (App\Models\Language::allowed() as $code => $name)
                        <li class="{{ config('language.flags.li_class') }}">
                            <a href="{{ route('back', [$code]) }}">
                                <img src="{{ url('img/flags/'.$code.'.png') }}" alt="{{ $name }}"
                                     class="img-circle {{ $code }}"
                                     width="{{ config('language.flags.width') }}"/>
                                    {{ $name }}
                                @if (auth()->user()->locale == $code)
                                    <span class="text-orange text-bold">(@lang('common.active')!)</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="dropdown hoe-rheader-submenu hoe-header-profile">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span>
            @if (auth()->user()->image != null)
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
                        <a href="{{ route('users.show', ['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-user"></i> @lang('user.my-profile')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_settings', ['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-cogs"></i> @lang('user.my-settings')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_privacy', ['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-eye"></i> @lang('user.my-privacy')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_security', ['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-shield-alt"></i> @lang('user.my-security')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bookmarks.index',['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-bookmark"></i> @lang('user.my-bookmarks')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user_requested',['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-question"></i> @lang('user.my-requested')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wishes.index', ['username' => auth()->user()->username]) }}">
                            <i class="{{ config('other.font-awesome') }} fa-clipboard-list"></i> @lang('user.my-wishlist')
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}">
                            <i class="{{ config('other.font-awesome') }} fa-sign-out"></i> @lang('auth.logout')
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        </li>
    </div>
</header>
