<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">

  <head>
    <meta charset="UTF-8">
    @section('title')
    <title>{{ Config::get('other.title') }} - {{ Config::get('other.subTitle') }}</title>
    @show

    @section('meta')
    <meta name="description" content="{{ Config::get('other.meta_description') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="{{ Config::get('other.title') }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ url('/img/rlm.png') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="_base_url" content="{{ route('home') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @show

    <link rel="shortcut icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('/favicon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ url('css/nav/hoe.css?v=05') }}">
    <link rel="stylesheet" href="{{ url('css/main/app.css?v=04') }}">
    <link rel="stylesheet" href="{{ url('css/main/custom.css?v=35') }}">
    @if(Auth::check()) @if(Auth::user()->style != 0)
    <link rel="stylesheet" href="{{ url('css/main/dark.css?v=02') }}">
    @endif @endif
    <link rel="stylesheet" href="{{ url('css/main/advbuttons.css?v=02') }}">
    <link rel="stylesheet" href="{{ url('css/vendor/vendor.min.css') }}" />
    @yield('stylesheets')

    @php $bg = rand(1, 8); $bgchange = $bg.".jpg"; @endphp
  </head>

  @if(Auth::user()->nav == 0)
  <body hoe-navigation-type="vertical-compact" hoe-nav-placement="left" theme-layout="wide-layout">
  @else
  <body hoe-navigation-type="vertical" hoe-nav-placement="left" theme-layout="wide-layout">
  @endif
    <div id="hoeapp-wrapper" class="hoe-hide-lpanel" hoe-device-type="desktop">
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
              <a href="#" class="dropdown-toggle icon-circle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="badge badge-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
              </a>
              <ul class="dropdown-menu ">
                <li class="hoe-submenu-label">
                  <h3><span class="bold">{{ Auth::user()->unreadNotifications->count() }} {{ trans('pm.unread') }}</span> {{ trans('common.notifications') }} <a href="#">{{ trans('common.view-all') }}</a></h3>
                </li>
                <li>
                  @foreach(Auth::user()->unreadNotifications as $notification)
                  <a href="{{ $notification->data['url'] }}" class="clearfix">
                    <i class="fa fa-bell-o red-text"></i>
                    <span class="notification-title">{{ $notification->data['title'] }}</span>
                    <span class="notification-ago">{{ $notification->created_at->diffForHumans() }}</span>
                    <p class="notification-message">{{ str_limit(strip_tags($notification->data['body']), 65) }}</p>
                  </a>
                  @endforeach
                </li>
              </ul>
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
              {{ Form::open(array('route' => 'search','role' => 'form', 'class' => 'hoe-searchbar')) }}
              <input type="text" placeholder="{{ trans('common.quick-search') }}..." name="name" id="name" class="form-control">
              <span class="search-icon"><i class="fa fa-search"></i></span>
              {{ Form::close() }}
            </li>
          </ul>

          <ul class="right-navbar">
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
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-logout"></i>{{ trans('auth.logout') }}</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                </li>
              </ul>
            </li>
          </ul>
      </li>
    </div>
  </header>

      <div id="hoeapp-container" hoe-color-type="lpanel-bg5" hoe-lpanel-effect="shrink">
        <aside id="hoe-left-panel" hoe-position-type="fixed" hoe-position-type="absolute">
          <ul class="nav panel-list">
            <li class="nav-level">{{ trans('common.navigation') }}</li>
            <li>
              <a href="{{ route('home') }}">
                <i class="livicon" data-name="home" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.home') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('torrents') }}">
                <i class="livicon" data-name="medal" data-size="18" data-c="#00bc8c" data-hc="#00bc8c" data-loop="true"></i>
                <span class="menu-text">{{ trans('torrent.torrents') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('forum_index') }}">
                <i class="livicon" data-name="doc-portrait" data-c="#5bc0de" data-hc="#5bc0de" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('forum.forums') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('upload') }}">
                <i class="livicon" data-name="upload" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.upload') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('requests') }}">
                <i class="livicon" data-name="folder-add" data-c="#f1c40f" data-hc="#f1c40f" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('request.requests') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('graveyard') }}">
                <i class="livicon" data-name="ghost" data-c="#7289DA" data-hc="#7289DA" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('graveyard.graveyard') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('bookmarks') }}">
                <i class="livicon" data-name="bookmark" data-c="silver" data-hc="silver" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('torrent.bookmark') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('stats') }}">
                <i class="livicon" data-name="barchart" data-c="pink" data-hc="pink" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.extra-stats') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('polls') }}">
                <i class="livicon" data-name="piechart" data-c="green" data-hc="green" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('poll.polls') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('bonus') }}">
                <i class="livicon" data-name="star-full" data-c="#BF55EC" data-hc="#BF55EC" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('bon.bon') }} {{ trans('bon.store') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('inbox', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}">
                <i class="livicon" data-name="mail" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                <span class="menu-text">{{ trans('pm.inbox') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('bug') }}">
                <i class="livicon" data-name="bug" data-c="#E74C3C" data-hc="#E74C3C" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.bug') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('home') }}/p/rules.1">
                <i class="livicon" data-name="info" data-c="#ecf0f1" data-hc="#ecf0f1" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.rules') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('home') }}/p/faq.3">
                <i class="livicon" data-name="question" data-c="#ecf0f1" data-hc="#ecf0f1" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('common.faq') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            <li>
              <a href="{{ route('rss', array('passkey' => Auth::user()->passkey)) }}">
                <i class="livicon" data-name="rss" data-c="orange" data-hc="orange" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('torrent.rss') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            @if(Auth::user()->group->is_modo)
            <li>
              <a href="{{ route('staff_dashboard') }}">
                <i class="livicon" data-name="gears" data-c="#E74C3C" data-hc="#E74C3C" data-size="18" data-loop="true"></i>
                <span class="menu-text">{{ trans('staff.staff-dashboard') }}</span>
                <span class="selected"></span>
              </a>
            </li>
            @endif
          </ul>
        </aside>

        <!-- Main Content -->
        <section id="main-content">
          <div class="inner-content">
            <div class="ratio-bar">
              <div class="container-fluid">
                <ul class="list-inline">
                  <li><i class="fa fa-user text-black"></i>
                    <a href="{{ route('profil', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="l-header-user-data-link">
                      <span class="badge-user" style="color:{{ Auth::user()->group->color }}"><strong>{{ Auth::user()->username }}</strong>@if(Auth::user()->getWarning() > 0) <i class="fa fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Active Warning"></i>@endif</span>
                    </a>
                  </li>
                  <li><i class="fa fa-group text-black"></i>
                    <span class="badge-user text-bold" style="color:{{ Auth::user()->group->color }}"><i class="{{ Auth::user()->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ Auth::user()->group->name }}"></i><strong> {{ Auth::user()->group->name }}</strong></span>
                  </li>
                  <li><i class="fa fa-arrow-up text-green text-bold"></i> {{ trans('common.upload') }}: {{ Auth::user()->getUploaded() }}</li>
                  <li><i class="fa fa-arrow-down text-red text-bold"></i> {{ trans('common.download') }}: {{ Auth::user()->getDownloaded() }}</li>
                  <li><i class="fa fa-signal text-blue text-bold"></i> {{ trans('common.ratio') }}: {{ Auth::user()->getRatioString() }}</li>
                  <li><i class="fa fa-exchange text-orange text-bold"></i> {{ trans('common.buffer') }}: {{ Auth::user()->untilRatio(config('other.ratio')) }}</li>
                  <li><i class="fa fa-upload text-green text-bold"></i>
                    <a href="{{ route('myactive', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" title="My Active Torrents"><span class="text-blue"> Seeding:</span></a> {{ Auth::user()->getSeeding() }}
                  </li>
                  <li><i class="fa fa-download text-red text-bold"></i>
                    <a href="{{ route('myactive', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" title="My Active Torrents"><span class="text-blue"> Leeching:</span></a> {{ Auth::user()->getLeeching() }}
                  </li>
                  <li><i class="fa fa-exclamation-circle text-orange text-bold"></i>
                    <a href="#" title="Hit and Run Count"><span class="text-blue"> {{ trans('common.warnings') }}:</span></a> {{ Auth::user()->getWarning() }}
                  </li>
                  <li><i class="fa fa-star text-purple text-bold"></i>
                    <a href="{{ route('bonus') }}" title="My Bonus Points"><span class="text-blue"> {{ trans('bon.bon') }}:</span></a> {{ Auth::user()->getSeedbonus() }}
                  </li>
                </ul>
              </div>
            </div>


            <ol class="breadcrumb">
              <li>
                <a href="{{ route('home') }}">
                  <i class="livicon" data-name="home" data-size="16" data-color="#000"></i> {{ trans('common.home') }}
                </a>
              </li>
              @yield('breadcrumb')
            </ol>


            <!-- Cookie Consent -->
            @include('cookieConsent::index')
            <!-- /Cookie Consent -->

            <!-- Site Alerts -->
            @if (config('other.freeleech') == true || config('other.invite-only') == false || config('other.doubleup') == true)
            <div class="alert alert-info" id="alert1">
              <center>
                <span>
                  @if(config('other.freeleech') == true) Global Freeleech Mode Activated! @endif
                  @if(config('other.invite-only') == false) Open Registration Activated! @endif
                  @if(config('other.doubleup') == true) Global Double Upload Activated! @endif
                </span>
                <strong><div id="promotions"></div></strong></center>
            </div>
            @endif
            <!-- /Site Alerts -->

            @if(Auth::user()->group_id == 1)
            <div class="container">
              <div class="jumbotron shadowed">
                <div class="container">
                  <h1 class="mt-5 text-center">
                    <i class="fa fa-times text-danger"></i> Error: You Need To Validate Your Account
                  </h1>
                  <div class="separator"></div>
                  <p class="text-center">You are not authorized to access this page. Check your email and validate your account!</p>
                </div>
              </div>
            </div>

            @elseif(Auth::user()->group->id == 5)
            <div class="container">
              <div class="jumbotron shadowed">
                <div class="container">
                  <h1 class="mt-5 text-center">
                    <i class="fa fa-times text-danger"></i> Error: Your Account Has Been Banned!
                  </h1>
                  <div class="separator"></div>
                  <p class="text-center">You are not authorized to access this page. You were a naughty member! Believe There Is A Error? Join Our Support Channel <a href="https://anon.to/?https://discord.gg/SHT8GDj">HERE</a></p>
                  <br> @php $banreason = DB::table('ban')->where('owned_by', '=', Auth::user()->id)->get(); @endphp
                  <span class="text-bold text-red">
                    @foreach($banreason as $reason)
                      You Were Banned On {{ $reason->created_at }}, For The Following Reason: <br> {{ $reason->ban_reason }} <br><br>
                    @endforeach
                  </span>
                </div>
              </div>
            </div>

            @else(Auth::check())

            @yield('content')

            @include('partials.footer')

            <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button">
              <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
            </a>
          </div>
        </section>
        <!-- /Main Content -->
      </div>
      </div>
      @endif

      <script type="text/javascript" src="{{ url('js/vendor/app.js?v=01') }}"></script>
      <script type="text/javascript" src="{{ url('js/hoe.js') }}"></script>
      <script type="text/javascript" src="{{ url('js/emoji.js') }}"></script>

      @if(Auth::check()) @if(Auth::user()->style != 0)
      <link rel="stylesheet" href="{{ url('files/wysibb/theme/dark/wbbtheme.css') }}" />
      @endif @endif

      <script type="text/javascript">
      $(document).ready(function(){
           $("#myCarousel").carousel({
               interval : 8000,
               pause: 'hover'
           });
      });
      </script>

      @if (config('other.freeleech') == true || config('other.invite-only') == false || config('other.doubleup') == true)
      <script type="text/javascript">
        CountDownTimer('12/01/2017 3:00 PM EST', 'promotions');

        function CountDownTimer(dt, id) {
          var end = new Date(dt);

          var _second = 1000;
          var _minute = _second * 60;
          var _hour = _minute * 60;
          var _day = _hour * 24;
          var timer;

          function showRemaining() {
            var now = new Date();
            var distance = end - now;
            if (distance < 0) {

              clearInterval(timer);
              document.getElementById(id).innerHTML = 'EXPIRED!';

              return;
            }
            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            var seconds = Math.floor((distance % _minute) / _second);

            document.getElementById(id).innerHTML = days + 'day(s)';
            document.getElementById(id).innerHTML += hours + 'hour(s)';
            document.getElementById(id).innerHTML += minutes + 'minute(s)';
            document.getElementById(id).innerHTML += seconds + 'second(s)';
          }

          timer = setInterval(showRemaining, 1000);
        }
      </script>
      @endif

      @if(Session::has('achievement'))
      <script type="text/javascript">
        swal({
          title: 'Awesome !',
          text: 'You Unlocked "{{Session::get('achievement')}}" Achievment',
          type: 'success'
        });
      </script>
      @endif

      {!! Toastr::message() !!}
      @yield('javascripts')
      @yield('scripts')

      @if(Config::get('app.debug') == false)
      <!-- INSERT YOUR ANALYTICS CODE HERE -->
      @else
      <!-- INSERT DEBUG CODE HERE -->
      @endif
    </div>
  </body>

</html>
