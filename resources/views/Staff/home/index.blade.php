@extends('layout.default')

@section('title')
    <title>Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="box">
            <div class="header gradient pink">
                <div class="inner_content">
                    <h1>Staff Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            @include('partials.dashboardmenu')

            <div class="col-sm-5 col-lg-5">
                <div class="block">
                    <div class="panel-body">
                        <h2 class="text-bold text-center text-green">
                            <i class=" {{ config('other.font-awesome') }} fa-terminal"></i> Codebase
                        </h2>
                        <h3 class="text-bold text-center">Currently Running {{ config('unit3d.codebase') }} {{ config('unit3d.version') }}</h3>
                        <version></version>
                    </div>
                </div>
            </div>

            <div class="col-sm-5 col-lg-5">
                <div class="block">
                    <div class="panel-body">
                    @if (request()->secure())
                        <h2 class="text-bold text-center text-green">
                            <i class=" {{ config('other.font-awesome') }} fa-lock"></i> SSL Cert
                        </h2>
                        <h3 class="text-bold text-center">
                            {{ config('app.url') }} -- <span class="text-muted">@if ($certificate->isValid() == true) VALID @else INVALID @endif</span>
                        </h3>
                        <div style="padding-top: 15px">
                            <span class="text-red text-left">Issued By: {{ $certificate->getIssuer() }}</span>
                            <span class="text-red" style="float: right">Expires: {{ $certificate->expirationDate()->diffForHumans() }}</span>
                        </div>
                    @else
                        <h2 class="text-bold text-center text-red">
                            <i class=" {{ config('other.font-awesome') }} fa-unlock"></i> SSL Cert
                        </h2>
                        <h3 class="text-bold text-center">
                            {{ config('app.url') }} -- <span class="text-muted">Connection Not Secure</span>
                        </h3>
                        <div style="padding-top: 15px">
                            <span class="text-red text-left">Issued By: N/A}</span>
                            <span class="text-red" style="float: right">Expires: N/A</span>
                        </div>
                    @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-lg-10">
                <div class="block" style=" margin-top: 30px;">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <u>{{ config('other.title') }} Statistics</u>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('moderation') }}">
                            <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                                <div class="lightbluebg no-radius">
                                    <div class="panel-body squarebox square_boxs">
                                        <div class="col-xs-12 pull-left nopadmar">
                                            <div class="row">
                                                <div class="square_box col-xs-7 text-right">
                                                    <span class="text-bold" style="font-size: 18px; padding-bottom: 10px;">Total Torrents</span>
                                                    <div class="number" id="myTargetElement1"></div>
                                                </div>
                                                <i class="{{ config('other.font-awesome') }} fa-magnet pull-right"
                                                   style=" font-size: 57px; margin-bottom: 15px"></i>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <small class="stat-label">Pending</small>
                                                    <h4 id="myTargetElement1.1"></h4>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <small class="stat-label">Rejected</small>
                                                    <h4 id="myTargetElement1.2"></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInDownBig">
                            <div class="redbg no-radius">
                                <div class="panel-body squarebox square_boxs">
                                    <div class="col-xs-12 pull-left nopadmar">
                                        <div class="row">
                                            <div class="square_box col-xs-7 pull-left">
                                                <span class="text-bold" style="font-size: 18px; padding-bottom: 10px;">Peers</span>
                                                <div class="number" id="myTargetElement2"></div>
                                            </div>
                                            <i class="{{ config('other.font-awesome') }} fa-wifi pull-right"
                                               style=" font-size: 57px; margin-bottom: 15px"></i>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <small class="stat-label">Seeders</small>
                                                <h4 id="myTargetElement2.1"></h4>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <small class="stat-label">Leechers</small>
                                                <h4 id="myTargetElement2.2"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
                            <div class="goldbg no-radius">
                                <div class="panel-body squarebox square_boxs">
                                    <div class="col-xs-12 pull-left nopadmar">
                                        <div class="row">
                                            <div class="square_box col-xs-7 pull-left">
                                                <span class="text-bold" style="font-size: 18px; padding-bottom: 10px;">Seedboxes</span>
                                                <div class="number" id="myTargetElement3"></div>
                                            </div>
                                            <i class="{{ config('other.font-awesome') }} fa-server pull-right"
                                               style=" font-size: 57px; margin-bottom: 15px"></i>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <small class="stat-label">Users</small>
                                                <h4 id="myTargetElement3.1"></h4>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <small class="stat-label">Torrents</small>
                                                <h4 id="myTargetElement3.2"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('user_search') }}">
                            <div class="col-lg-4 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                                <div class="palebluecolorbg no-radius">
                                    <div class="panel-body squarebox square_boxs">
                                        <div class="col-xs-12 pull-left nopadmar">
                                            <div class="row">
                                                <div class="square_box col-xs-7 pull-left">
                                                    <span class="text-bold" style="font-size: 18px; padding-bottom: 10px;">Users</span>
                                                    <div class="number" id="myTargetElement4"></div>
                                                </div>
                                                <i class="{{ config('other.font-awesome') }} fa-users pull-right"
                                                   style=" font-size: 57px; margin-bottom: 15px"></i>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <small class="stat-label">Validating</small>
                                                    <h4 id="myTargetElement4.1"></h4>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <small class="stat-label">Banned</small>
                                                    <h4 id="myTargetElement4.2"></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('getReports') }}">
                            <div class="col-lg-4 col-sm-6 col-md-6 margin_10 animated fadeInUpBig">
                                <div class="purplebg no-radius">
                                    <div class="panel-body squarebox square_boxs">
                                        <div class="col-xs-12 pull-left nopadmar">
                                            <div class="row">
                                                <div class="square_box col-xs-7 pull-left">
                                                    <span class="text-bold" style="font-size: 18px; padding-bottom: 10px;">Reports</span>
                                                    <div class="number" id="myTargetElement5"></div>
                                                </div>
                                                <i class="{{ config('other.font-awesome') }} fa-clipboard-list pull-right"
                                                   style=" font-size: 57px; margin-bottom: 15px"></i>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <small class="stat-label">Solved</small>
                                                    <h4 id="myTargetElement5.1"></h4>
                                                </div>
                                                <div class="col-xs-6 text-right">
                                                    <small class="stat-label">Unsolved</small>
                                                    <h4 id="myTargetElement5.2"></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-sm-10 col-lg-10">
                <div class="block" style=" margin-top: 30px;">
                    <div class="panel-heading">
                        <h1 class="text-center">
                            <u>Server Information</u>
                        </h1>
                    </div>
                    <div class="panel-body">

                        <div class="row black-list">
                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">OS</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">{{ $basic['os'] }}</span>
                                    <i class="fal fa-desktop black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">PHP</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">php{{ $basic['php'] }}</span>
                                    <i class="fal fa-terminal black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">DATABASE</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">{{ $basic['database'] }}</span>
                                    <i class="fal fa-database black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">LARAVEL</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">Ver. {{ $basic['laravel'] }}</span>
                                    <i class="fal fa-code-merge black-icon text-green"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row black-list">
                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">RAM</h1>
                                    <span class="badge-user">Total: {{ $ram['total'] }}</span>
                                    <br>
                                    <span class="badge-user">Free: {{ $ram['free'] }}</span>
                                    <span class="badge-user">Used: {{ $ram['used'] }}</span>
                                    <i class="fal fa-memory black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">DISK</h1>
                                    <span class="badge-user">Total: {{ $disk['total'] }}</span>
                                    <br>
                                    <span class="badge-user">Free: {{ $disk['free'] }}</span>
                                    <span class="badge-user">Used: {{ $disk['used'] }}</span>
                                    <i class="fal fa-hdd black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #fff;">LOAD</h1>
                                    <span class="badge-user">Average: {{ $avg }}</span>
                                    <br>
                                    <span class="badge-user">Estimated</span>
                                    <i class="fal fa-balance-scale-right black-icon text-green"></i>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('javascripts')
    <script>
      var useOnComplete = false,
        useEasing = false,
        useGrouping = false,
        options = {
          useEasing: useEasing, // toggle easing
          useGrouping: useGrouping, // 1,000,000 vs 1000000
          separator: ',', // character to use as a separator
          decimal: '.' // character to use as a decimal
        };

      // Torrents
      var demo = new CountUp('myTargetElement1', 0, {{ $num_torrent }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement1.1', 0, {{ $pending }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement1.2', 0, {{ $rejected }}, 0, 4, options);
      demo.start();

      // Peers
      var demo = new CountUp('myTargetElement2', 0, {{ $peers }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement2.1', 0, {{ $seeders }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement2.2', 0, {{ $leechers }}, 0, 4, options);
      demo.start();

      // Seedboxes
      var demo = new CountUp('myTargetElement3', 0, {{ $seedboxes }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement3.1', 0, {{ $highspeed_users }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement3.2', 0, {{ $highspeed_torrents }}, 0, 4, options);
      demo.start();

      // Users
      var demo = new CountUp('myTargetElement4', 0, {{ $num_user }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement4.1', 0, {{ $validating }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement4.2', 0, {{ $banned }}, 0, 4, options);
      demo.start();

      // Reports
      var demo = new CountUp('myTargetElement5', 0, {{ $reports }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement5.1', 0, {{ $solved }}, 0, 4, options);
      demo.start();
      var demo = new CountUp('myTargetElement5.2', 0, {{ $unsolved }}, 0, 4, options);
      demo.start();
    </script>
@endsection
