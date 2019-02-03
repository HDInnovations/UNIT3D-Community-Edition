@extends('layout.default')

@section('title')
    <title>@lang('stat.stats') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('stat.stats')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient red">
            <div class="inner_content">
                <h1>@lang('stat.site-stats')</h1>
            </div>
        </div>
        <div class="stats">
            <div class="content">
                <div class="inner_content">
                    <h1>@lang('stat.nerd-stats')</h1>
                    <p>@lang('stat.nerd-stats-desc'). <b>(Updated Every 60 Minutes!)</b></p>

                    <div class="inner_stats">
                        @foreach ($categories as $category)
                            <div class="stat">
                                <p>{{ $category->torrents_count }}</p>
                                <span class="badge-extra">{{ $category->name }} @lang('torrent.category')</span>
                            </div>
                        @endforeach

                        <div class="stat">
                            <p>{{ $num_torrent }}</p>
                            <span class="badge-extra">@lang('stat.total-torrents')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $num_hd }}</p>
                            <span class="badge-extra">HD @lang('torrent.torrents')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $num_sd }}</p>
                            <span class="badge-extra">SD @lang('torrent.torrents')</span>
                        </div>

                        <div class="stat">
                            <p>{{ App\Helpers\StringHelper::formatBytes($torrent_size  , 2) }}</p>
                            <span class="badge-extra">@lang('stat.total-torrents') @lang('torrent.size')</span>
                        </div>
                        <br>

                        <div class="stat">
                            <p>{{ $all_user }}</p>
                            <span class="badge-extra">All @lang('common.users')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $active_user }}</p>
                            <span class="badge-extra">Active @lang('common.users')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $disabled_user }}</p>
                            <span class="badge-extra">Disabled @lang('common.users')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $pruned_user }}</p>
                            <span class="badge-extra">Pruned @lang('common.users')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $banned_user }}</p>
                            <span class="badge-extra">Banned @lang('common.users')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $num_seeders }}</p>
                            <span class="badge-extra">@lang('torrent.seeders')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $num_leechers }}</p>
                            <span class="badge-extra">@lang('torrent.leechers')</span>
                        </div>

                        <div class="stat">
                            <p>{{ $num_peers }}</p>
                            <span class="badge-extra">@lang('torrent.peers')</span>
                        </div>

                        <br>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_upload ,2) }}</p>
                            <span class="badge-extra">Real @lang('stat.total-upload')</span>
                        </div>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_download ,2) }}</p>
                            <span class="badge-extra">Real @lang('stat.total-download')</span>
                        </div>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($actual_up_down ,2) }}</p>
                            <span class="badge-extra">Real @lang('stat.total-traffic')</span>
                        </div>

                        <br>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_upload ,2) }}</p>
                            <span class="badge-extra">Credited @lang('stat.total-upload')</span>
                        </div>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_download ,2) }}</p>
                            <span class="badge-extra">Credited @lang('stat.total-download')</span>
                        </div>

                        <div class="stat">
                            <p>{{ \App\Helpers\StringHelper::formatBytes($credited_up_down ,2) }}</p>
                            <span class="badge-extra">Credited @lang('stat.total-traffic')</span>
                        </div>

                    </div>
                </div>
                <img src="{{ url('img/sheldon.png') }}" width="321" height="379">
            </div>
        </div>
        <br>
        <h3 class="text-center">@lang('stat.select-category')</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="well well-sm mt-20">
                    <a href="{{ route('uploaded') }}">
                        <p class="lead text-green text-center">@lang('common.users')</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="well well-sm mt-20">
                    <a href="{{ route('seeded') }}">
                        <p class="lead text-blue text-center">@lang('torrent.torrents')</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="well well-sm mt-20">
                    <a href="{{ route('bountied') }}">
                        <p class="lead text-orange text-center">@lang('request.requests')</p>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="well well-sm mt-20">
                    <a href="{{ route('groups') }}">
                        <p class="lead text-red text-center">@lang('common.groups')</p>
                    </a>
                </div>
            </div>
            {{--<div class="col-sm-4">
              <div class="well well-sm mt-20">
                <a href="#">
                  <p class="lead text-pink text-center">@lang('commmon.teams')</p>
                </a>
              </div>
            </div>--}}
            </br>
            </br>
        </div>
        <p class="text-purple text-center text-mono">@lang('stat.stats-format')</p>
    </div>
@endsection
