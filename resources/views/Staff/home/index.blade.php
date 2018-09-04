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

            <div class="col-sm-10 col-lg-10">
                <div class="block">
                    <div class="panel-heading">
                        <h1>
                            <u>Stats:</u>
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
                                                    <span class="text-bold text-green" style="font-size: 18px; padding-bottom: 10px;">Total Torrents</span>
                                                    <div class="number" id="myTargetElement1"></div>
                                                </div>
                                                <i class="livicon  pull-right" data-name="doc-portrait" data-l="true"
                                                   data-c="#fff"
                                                   data-hc="#fff" data-s="70"></i>
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
                                                <span class="text-bold text-green" style="font-size: 18px; padding-bottom: 10px;">Peers</span>
                                                <div class="number" id="myTargetElement2"></div>
                                            </div>
                                            <i class="livicon pull-right" data-name="wifi-alt" data-l="true"
                                               data-c="#fff"
                                               data-hc="#fff" data-s="70"></i>
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
                                                <span class="text-bold text-green" style="font-size: 18px; padding-bottom: 10px;">Seedboxes</span>
                                                <div class="number" id="myTargetElement3"></div>
                                            </div>
                                            <i class="livicon pull-right" data-name="servers" data-l="true"
                                               data-c="#fff"
                                               data-hc="#fff" data-s="70"></i>
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
                                                    <span class="text-bold text-green" style="font-size: 18px; padding-bottom: 10px;">Users</span>
                                                    <div class="number" id="myTargetElement4"></div>
                                                </div>
                                                <i class="livicon pull-right" data-name="users" data-l="true"
                                                   data-c="#fff"
                                                   data-hc="#fff" data-s="70"></i>
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
                                                    <span class="text-bold text-green" style="font-size: 18px; padding-bottom: 10px;">Reports</span>
                                                    <div class="number" id="myTargetElement5"></div>
                                                </div>
                                                <i class="livicon pull-right" data-name="list" data-l="true"
                                                   data-c="#fff"
                                                   data-hc="#fff" data-s="70"></i>
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
                <div class="block">
                    <div class="panel-heading">
                        <h1>
                            <u>Codebase:</u>
                        </h1>
                    </div>
                    <div class="panel-body">
                        <span class="text-red text-bold text-center"><h2>Currently Running {{ config('unit3d.codebase') }} {{ config('unit3d.version') }}</h2></span>
                        <version></version>
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

      // Reports
      var demo = new CountUp('myTargetElement6', 0, {{ $pollCount }}, 0, 4, options);
      demo.start()
    </script>
@endsection
