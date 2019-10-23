@extends('layout.default')

@section('title')
    <title>Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
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
                            {{ config('app.url') }}
                        </h3>
                        <div class="text-center" style="padding-top: 15px;">
                            <span class="text-red">Issued By: {{ $certificate->getIssuer() }}</span>
                            <br>
                            <span class="text-red">Expires: {{ $certificate->expirationDate()->diffForHumans() }}</span>
                        </div>
                    @else
                        <h2 class="text-bold text-center text-red">
                            <i class=" {{ config('other.font-awesome') }} fa-unlock"></i> SSL Cert
                        </h2>
                        <h3 class="text-bold text-center">
                            {{ config('app.url') }} -- <span class="text-muted">Connection Not Secure</span>
                        </h3>
                        <div style="padding-top: 15px;">
                            <span class="text-red text-left">Issued By: N/A}</span>
                            <span class="text-red" style="float: right;">Expires: N/A</span>
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

                        <div class="row black-list">
                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">Torrents</h1>
                                    <span class="badge-user">Total: {{ $num_torrent }}</span>
                                    <br>
                                    <span class="badge-user">Pending: {{ $pending }}</span>
                                    <span class="badge-user">Rejected: {{ $rejected }}</span>
                                    <i class="fal fa-magnet black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">Peers</h1>
                                    <span class="badge-user">Total: {{ $peers }}</span>
                                    <br>
                                    <span class="badge-user">Seeders: {{ $seeders }}</span>
                                    <span class="badge-user">Leechers: {{ $leechers }}</span>
                                    <i class="fal fa-wifi black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">Users</h1>
                                    <span class="badge-user">Total: {{ $num_user }}</span>
                                    <br>
                                    <span class="badge-user">Validating: {{ $validating }}</span>
                                    <span class="badge-user">Banned: {{ $banned }}</span>
                                    <i class="fal fa-users black-icon text-green"></i>
                                </div>
                            </div>
                        </div>

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
                                    <h1 style=" color: #ffffff;">OS</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">{{ $basic['os'] }}</span>
                                    <i class="fal fa-desktop black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">PHP</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">php{{ $basic['php'] }}</span>
                                    <i class="fal fa-terminal black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">DATABASE</h1>
                                    <span class="badge-user">Currently Running</span>
                                    <br>
                                    <span class="badge-user">{{ $basic['database'] }}</span>
                                    <i class="fal fa-database black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3 col-md-3">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">LARAVEL</h1>
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
                                    <h1 style=" color: #ffffff;">RAM</h1>
                                    <span class="badge-user">Total: {{ $ram['total'] }}</span>
                                    <br>
                                    <span class="badge-user">Free: {{ $ram['free'] }}</span>
                                    <span class="badge-user">Used: {{ $ram['used'] }}</span>
                                    <i class="fal fa-memory black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">DISK</h1>
                                    <span class="badge-user">Total: {{ $disk['total'] }}</span>
                                    <br>
                                    <span class="badge-user">Free: {{ $disk['free'] }}</span>
                                    <span class="badge-user">Used: {{ $disk['used'] }}</span>
                                    <i class="fal fa-hdd black-icon text-green"></i>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-4 col-md-4">
                                <div class="text-center black-item">
                                    <h1 style=" color: #ffffff;">LOAD</h1>
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
@endsection