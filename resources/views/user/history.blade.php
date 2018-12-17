@extends('layout.default')

@section('title')
    <title>@lang('user.history-table') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('myhistory', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('user.history-table')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
    <span class="badge-user" style="float: right;"><strong>@lang('user.total-download'):</strong>
        <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his_downl,2) }}</span>
        <span class="badge-extra text-orange" data-toggle="tooltip"
              data-original-title="@lang('user.credited-download')">{{ App\Helpers\StringHelper::formatBytes($his_downl_cre,2) }}</span>
    </span>
        <span class="badge-user" style="float: right;"><strong>@lang('user.total-upload'):</strong>
        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his_upl,2) }}</span>
        <span class="badge-extra text-blue" data-toggle="tooltip"
              data-original-title="@lang('user.credited-upload')">{{ App\Helpers\StringHelper::formatBytes($his_upl_cre,2) }}</span>
    </span>
        <h1 class="title">
            @lang('user.history-table')
            <a href="{{ route('download_history_torrents', ['username' => $user->username, 'id' => $user->id]) }}" role="button" class="btn btn-labeled btn-success">
                <span class='btn-label'>
                    <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('torrent.download-all') @lang('torrent.torrent')
                </span>
            </a>
        </h1>
        <div class="block">
            <!-- History -->
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <div class="head"><strong>@lang('user.torrents-history')</strong></div>
                    <thead>
                    <th>@sortablelink('name', trans('torrent.name'))</th>
                    <th>@sortablelink('agent', trans('torrent.agent'))</th>
                    <th>@sortablelink('active', trans('common.active'))</th>
                    <th>@sortablelink('seeder', trans('torrent.seeder'))</th>
                    <th>@sortablelink('uploaded', trans('torrent.uploaded'))</th>
                    <th>@sortablelink('downloaded', trans('torrent.downloaded'))</th>
                    <th>@sortablelink('seedtime', trans('torrent.seedtime'))</th>
                    <th>@sortablelink('created_at', trans('torrent.created_at'))</th>
                    <th>@sortablelink('updated_at', trans('torrent.updated_at'))</th>
                    <th>@sortablelink('completed_at', trans('torrent.completed_at'))</th>
                    <th>@sortablelink('prewarn', trans('torrent.prewarn'))</th>
                    <th>@sortablelink('hitrun', trans('torrent.hitrun'))</th>
                    <th>@sortablelink('immune', trans('torrent.immune'))</th>
                    </thead>
                    <tbody>
                    @foreach ($history as $his)
                        <tr>
                            <td>
                                <a class="view-torrent" href="{{ route('torrent', ['slug' => $his->torrent->slug, 'id' => $his->torrent->id]) }}">
                                    {{ $his->torrent->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge-extra text-purple">{{ $his->agent ? $his->agent : trans('common.unknown') }}</span>
                            </td>
                            @if ($his->active == 1)
                                <td class="text-green">@lang('common.yes')</td> @else
                                <td class="text-red">@lang('common.no')</td> @endif
                            @if ($his->seeder == 1)
                                <td class="text-green">@lang('common.yes')</td> @else
                                <td class="text-red">@lang('common.no')</td> @endif
                            <td>
                                <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his->actual_uploaded , 2) }}</span>
                                <span class="badge-extra text-blue" data-toggle="tooltip"
                                      data-original-title="@lang('user.credited-upload')">{{ App\Helpers\StringHelper::formatBytes($his->uploaded , 2) }}</span>
                            </td>
                            <td>
                                <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his->actual_downloaded , 2) }}</span>
                                <span class="badge-extra text-orange" data-toggle="tooltip"
                                      data-original-title="@lang('user.credited-download')">{{ App\Helpers\StringHelper::formatBytes($his->downloaded , 2) }}</span>
                            </td>
                            @if ($his->seedtime < config('hitrun.seedtime'))
                                <td>
                                    <span class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                                </td>
                            @else
                                <td>
                                    <span class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                                </td>
                            @endif
                            <td>{{ $his->created_at->diffForHumans() }}</td>
                            <td>{{ $his->updated_at->diffForHumans() }}</td>
                            <td>{{ $his->completed_at ? $his->completed_at->diffForHumans() : "N/A"}}</td>
                            @if ($his->prewarn == 1)
                                <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                            @else
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                            @endif
                            @if ($his->hitrun == 1)
                                <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                            @else
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                            @endif
                            @if ($his->immune == 1)
                                <td><i class="{{ config('other.font-awesome') }} fa-check text-green"></i></td>
                            @else
                                <td><i class="{{ config('other.font-awesome') }} fa-times text-red"></i></td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    {{ $history->appends(request()->except('page'))->render() }}
                </div>
            </div>
        </div>
@endsection
