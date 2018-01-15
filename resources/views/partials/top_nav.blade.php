<header id="hoe-header" hoe-color-type="header-bg5" hoe-lpanel-effect="shrink" class="hoe-minimized-lpanel">
  <div class="hoe-left-header" hoe-position-type="fixed">
    <a href="{{ route('home') }}"><div class="banner"><i class="fa fa-rocket" style="display: inline;"></i> <span>{{ Config::get('other.title') }}</span></div></a>
    <span class="hoe-sidebar-toggle"><a href="#"></a></span>
  </div>
  <div class="hoe-right-header" hoe-position-type="relative" hoe-color-type="header-bg5">
    <span class="hoe-sidebar-toggle"><a href="#"></a></span>
    <ul class="left-navbar">
      <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
        @php $pm = DB::table('private_messages')->where('reciever_id', '=', Auth::user()->id)->where('read', '=', '0')->count(); @endphp
        <a href="{{ route('inbox', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="dropdown-toggle icon-circle">
          <i class="fa fa-envelope-o text-blue"></i>
          @if($pm > 0)
          <span class="badge badge-danger">{{ $pm }}</span>
          @endif
        </a>
      </li>

      <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
        <a href="{{ route('get_notifications') }}" class="dropdown-toggle icon-circle">
          <i class="fa fa-bell-o"></i>
          @if(Auth::user()->unreadNotifications->count() > 0)
          <span class="badge badge-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
          @endif
        </a>
      </li>

      <li class="dropdown hoe-rheader-submenu message-notification left-min-30">
        <a href="{{ route('achievements', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="dropdown-toggle icon-circle">
          <i class="fa fa-trophy text-gold"></i>
        </a>
      </li>

      @if(Auth::user()->group->is_modo)
      <li class="dropdown hoe-rheader-submenu message-notification left-min-65">
        <a href="#" class="dropdown-toggle icon-circle" data-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-tasks text-red"></i>
          @php $modder = DB::table('torrents')->where('status', '=', '0')->count(); @endphp
          @if($modder > 0)
          <span class="badge badge-danger">{{ $modder }}</span>
          @endif
        </a>
        <ul class="dropdown-menu ">
          <li class="hoe-submenu-label">
            <h3> {{ trans('staff.you-have') }} <span class="bold">{{ $modder }}  </span>{{ trans('common.pending') }} {{ trans('torrent.torrents') }} <a href="{{ route('moderation') }}">{{ trans('common.view-all') }}</a></h3>
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
        {{ Form::open(['action'=>'TorrentController@torrents','method'=>'get','role' => 'form','class'=>'hoe-searchbar']) }}
        {{ Form::text('search',null,['id'=>'search','placeholder'=>'Quick Search...','class'=>'form-control']) }}
        <span class="search-icon"><i class="fa fa-search"></i></span>
        {{ Form::close() }}
      </li>
    </ul>

    <ul class="right-navbar">
        <li class="dropdown hoe-rheader-submenu hoe-header-profile">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>
                <img src="{{ url('img/flags/'.strtolower(Auth::user()->locale).'.png') }}" class="img-circle" />
            </span>
          <span>Language <i class=" fa fa-angle-down"></i></span>
        </a>
        <ul class="dropdown-menu ">
            @foreach (App\Language::allowed() as $code => $name)
                <li class="{{ config('language.flags.li_class') }}">
                    <a href="{{ route('back', ['local' => $code]) }}">
                        <img src="{{ url('img/flags/'.strtolower($code).'.png') }}" alt="{{ $name }}" width="{{ config('language.flags.width') }}" /> &nbsp;{{ $name }} @if(Auth::user()->locale == $code)<span class="text-orange text-bold">(Active!)</span>@endif
                    </a>
                </li>
            @endforeach
        </ul>
      </li>
      <li class="dropdown hoe-rheader-submenu hoe-header-profile">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span>
            @if(Auth::user()->image != null)
              <img src="{{ url('files/img/' . Auth::user()->image) }}" alt="{{{ Auth::user()->username }}}" class="img-circle">
            @else
              <img src="{{ url('img/profil.png') }}" alt="{{{ Auth::user()->username }}}" class="img-circle">
            @endif
          </span>
          <span>{{ Auth::user()->username }} <i class=" fa fa-angle-down"></i></span>
        </a>
        <ul class="dropdown-menu ">
          <li><a href="{{ route('profil', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}"><i class="fa fa-user"></i>{{ trans('user.my-profile') }}</a></li>
          <li><a href="{{ route('lock') }}"><i class="fa fa-lock"></i>Lock Account</a></li>
          <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i>{{ trans('auth.logout') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
          </li>
        </ul>
      </li>
    </ul>
</li>
</div>
</header>
