<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LogViewer - Created by ARCANEDEV</title>
    <meta name="description" content="LogViewer">
    <meta name="author" content="ARCANEDEV">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700|Source+Sans+Pro:400,600' rel='stylesheet' type='text/css'>
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            padding-top: 50px;
            /* Margin bottom by footer height */
            margin-bottom: 50px;
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, sans-serif;
            font-weight: 600;
        }

        h1, h2, h3 {
            font-family: 'Montserrat', 'Helvetica Neue', Helvetica, sans-serif;
        }

        .sub-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #EEE;
        }

        .navbar-inverse {
            background-color: #1a237e;
            border-color: #1a237e;
        }

        .navbar-inverse .navbar-nav>.active>a,
        .navbar-inverse .navbar-nav>.active>a:focus,
        .navbar-inverse .navbar-nav>.active>a:hover {
            background-color: #3949ab;
        }

        .navbar-inverse .navbar-brand {
            color: #c5cae9;
        }

        .navbar-inverse .navbar-nav>li>a {
            color: #c5cae9;
        }

        .navbar-fixed-top {
            border: 0;
        }

        .main {
            padding: 20px;
        }

        .main .page-header {
            margin-top: 0;
        }

        footer.main-footer {
            position: absolute;
            padding: 10px 0;
            bottom: 0;
            width: 100%;
            background-color: #e8eaf6;
            font-weight: 600;
        }

        footer.main-footer p {
            margin: 0;
        }

        footer.main-footer i.fa.fa-heart {
            color: #C62828;
        }

        .pagination {
            margin: 0;
        }

        .pagination > li > a,
        .pagination > li > span {
            padding: 4px 10px;
        }

        .table-condensed > tbody > tr > td.stack,
        .table-condensed > tfoot > tr > td.stack,
        .table-condensed > thead > tr > td.stack {
            padding: 0;
            border-top: none;
            background-color: #F6F6F6;
            border-top: 1px solid #D1D1D1;
            max-width: 0;
            overflow-x: auto;
        }

        .table-condensed > tbody > tr > td > p {
          margin: 0;
        }

        .stack-content {
            padding: 8px;
            color: #AE0E0E;
            font-family: consolas, Menlo, Courier, monospace;
            font-size: 12px;
            font-weight: 400;
            white-space: pre-line;
        }

        .info-box.level {
            display: block;
            padding: 0;
            margin-bottom: 15px;
            min-height: 70px;
            background: #fff;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 2px;
        }

        .info-box.level .info-box-text,
        .info-box.level .info-box-number,
        .info-box.level .info-box-icon > i {
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
        }

        .info-box.level .info-box-text {
            display: block;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .info-box.level .info-box-content {
            padding: 5px 10px;
            margin-left: 70px;
        }

        .info-box.level .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }

        .info-box.level .info-box-icon {
            border-radius: 2px 0 0 2px;
            display: block;
            float: left;
            height: 70px; width: 70px;
            text-align: center;
            font-size: 40px;
            line-height: 70px;
            background: rgba(0,0,0,0.2);
        }

        .info-box.level .progress {
            background: rgba(0,0,0,0.2);
            margin: 5px -10px 5px -10px;
            height: 2px;
        }

        .info-box.level .progress .progress-bar {
            background: #fff;
        }

        .info-box.level-empty {
            opacity: .6;
            -webkit-filter: grayscale(1);
               -moz-filter: grayscale(1);
                -ms-filter: grayscale(1);
                    filter: grayscale(1);
            -webkit-transition: all 0.2s ease-in-out;
               -moz-transition: all 0.2s ease-in-out;
                 -o-transition: all 0.2s ease-in-out;
                    transition: all 0.2s ease-in-out;
            -webkit-transition-property: -webkit-filter, opacity;
               -moz-transition-property: -moz-filter, opacity;
                 -o-transition-property: filter, opacity;
                    transition-property: -webkit-filter, -moz-filter, -o-filter, filter, opacity;
        }

        .info-box.level-empty:hover {
            opacity: 1;
            -webkit-filter: grayscale(0);
               -moz-filter: grayscale(0);
                -ms-filter: grayscale(0);
                    filter: grayscale(0);
        }

        .level {
            padding: 2px 6px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
            border-radius: 2px;
            font-size: .9em;
            font-weight: 600;
        }

        .badge.level-all,
        .badge.level-emergency,
        .badge.level-alert,
        .badge.level-critical,
        .badge.level-error,
        .badge.level-warning,
        .badge.level-notice,
        .badge.level-info,
        .badge.level-debug,
        .level, .level i,
        .info-box.level-all,
        .info-box.level-emergency,
        .info-box.level-alert,
        .info-box.level-critical,
        .info-box.level-error,
        .info-box.level-warning,
        .info-box.level-notice,
        .info-box.level-info,
        .info-box.level-debug {
            color: #FFF;
        }

        .label-env {
            font-size: .85em;
        }

        .badge.level-all, .level.level-all, .info-box.level-all {
            background-color: {{ log_styler()->color('all') }};
        }

        .badge.level-emergency, .level.level-emergency, .info-box.level-emergency {
            background-color: {{ log_styler()->color('emergency') }};
        }

        .badge.level-alert, .level.level-alert, .info-box.level-alert  {
            background-color: {{ log_styler()->color('alert') }};
        }

        .badge.level-critical, .level.level-critical, .info-box.level-critical {
            background-color: {{ log_styler()->color('critical') }};
        }

        .badge.level-error, .level.level-error, .info-box.level-error {
            background-color: {{ log_styler()->color('error') }};
        }

        .badge.level-warning, .level.level-warning, .info-box.level-warning {
            background-color: {{ log_styler()->color('warning') }};
        }

        .badge.level-notice, .level.level-notice, .info-box.level-notice {
            background-color: {{ log_styler()->color('notice') }};
        }

        .badge.level-info, .level.level-info, .info-box.level-info {
            background-color: {{ log_styler()->color('info') }};
        }

        .badge.level-debug, .level.level-debug, .info-box.level-debug {
            background-color: {{ log_styler()->color('debug') }};
        }

        .badge.level-empty, .level.level-empty {
            background-color: {{ log_styler()->color('empty') }};
        }

        .badge.label-env, .label.label-env {
            background-color: #6A1B9A;
        }
    </style>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ route('log-viewer::dashboard') }}" class="navbar-brand">
                    <i class="fa fa-fw fa-book"></i> LogViewer
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="{{ Route::is('log-viewer::dashboard') ? 'active' : '' }}">
                        <a href="{{ route('log-viewer::dashboard') }}">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="{{ Route::is('log-viewer::logs.list') ? 'active' : '' }}">
                        <a href="{{ route('log-viewer::logs.list') }}">
                            <i class="fa fa-archive"></i> Logs
                        </a>
                    </li>
                    <li><a href="{{ route('staff_dashboard') }}">Return To Staff Panel</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Main container --}}
    <main class="container-fluid">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="main-footer">
        <div class="container">
            <p class="text-muted pull-left">
                LogViewer - <span class="label label-info">version {{ log_viewer()->version() }}</span>
            </p>
            <p class="text-muted pull-right">
                Created with <i class="fa fa-heart"></i> by ARCANEDEV <sup>&copy;</sup>
            </p>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
    <script>
        Chart.defaults.global.responsive      = true;
        Chart.defaults.global.scaleFontFamily = "'Source Sans Pro'";
        Chart.defaults.global.animationEasing = "easeOutQuart";
    </script>
    @yield('modals')
    @yield('scripts')
</body>
</html>
