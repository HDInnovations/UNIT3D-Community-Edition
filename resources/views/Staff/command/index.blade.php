@extends('layout.default')

@section('title')
    <title>Commands - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Commands - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.commands.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Commands</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header gradient silver">
                <div class="inner_content">
                    <h1>Command Shortcuts</h1>
                </div>
            </div>
    
            <br>
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Enable Maintenance Mode
                            </h3>
                            <h4 class="text-muted">This commands enables maintenance mode while whitelisting only you IP
                                Address.</h4>
                            <a href="{{ url('/dashboard/commands/maintance-enable') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Disable Maintenance Mode
                            </h3>
                            <h4 class="text-muted">This commands disables maintenance mode. Bringing the site backup for all
                                to access.</h4>
                            <a href="{{ url('/dashboard/commands/maintance-disable') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Clear Cache
                            </h3>
                            <h4 class="text-muted">This commands clears your sites cache. This cache depends on what driver
                                you are using.</h4>
                            <a href="{{ url('/dashboard/commands/clear-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <br>
                <br>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Clear View Cache
                            </h3>
                            <h4 class="text-muted">This commands clears your sites compiled views cache.</h4>
                            <a href="{{ url('/dashboard/commands/clear-view-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Clear Route Cache
                            </h3>
                            <h4 class="text-muted">This commands clears your sites compiled routes cache.</h4>
                            <a href="{{ url('/dashboard/commands/clear-route-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Clear Config Cache
                            </h3>
                            <h4 class="text-muted">This commands clears your sites compiled configs cache.</h4>
                            <a href="{{ url('/dashboard/commands/clear-config-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <br>
                <br>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Clear All Cache
                            </h3>
                            <h4 class="text-muted">This commands clears ALL of your sites cache.</h4>
                            <a href="{{ url('/dashboard/commands/clear-all-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Set All Cache
                            </h3>
                            <h4 class="text-muted">This commands sets ALL of your sites cache.</h4>
                            <a href="{{ url('/dashboard/commands/set-all-cache') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="block">
                        <div class="panel-body">
                            <h3 class="text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-terminal"></i> Send Test Email
                            </h3>
                            <h4 class="text-muted">This commands tests your email configuration.</h4>
                            <a href="{{ url('/dashboard/commands/test-email') }}" class="btn btn-sm btn-primary">Run
                                Command</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
