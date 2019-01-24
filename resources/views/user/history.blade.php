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
            <div class="block">
                <div class="button-holder">
                    <div class="button-left">
            <a href="{{ route('download_history_torrents', ['username' => $user->username, 'id' => $user->id]) }}" role="button" class="btn btn-labeled btn-success">
                <span class='btn-label'>
                    <i class='{{ config("other.font-awesome") }} fa-download'></i> @lang('torrent.download-all') @lang('torrent.torrents')
                </span>
            </a>
                    </div>
                    <div class="button-right">
    <span class="badge-user"><strong>@lang('user.total-download'):</strong>
        <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his_downl,2) }}</span>
        <span class="badge-extra text-orange" data-toggle="tooltip"
              data-original-title="@lang('user.credited-download')">{{ App\Helpers\StringHelper::formatBytes($his_downl_cre,2) }}</span>
    </span>
        <span class="badge-user"><strong>@lang('user.total-upload'):</strong>
        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his_upl,2) }}</span>
        <span class="badge-extra text-blue" data-toggle="tooltip"
              data-original-title="@lang('user.credited-upload')">{{ App\Helpers\StringHelper::formatBytes($his_upl_cre,2) }}</span>
    </span>
                    </div>
                </div>
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        @lang('user.history-table')
                    </h1>
                </div>
            </div>
            <div class="container well search mt-5">
                <form role="form" method="GET" action="UserController@myFilters" class="form-horizontal form-condensed form-torrent-search form-bordered">
                    @csrf

                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">Filters</label>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="active" value="1" class="userFilter" trigger="click"> Active
                    </label>
                </span>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="seeding" value="1" class="userFilter" trigger="click"> Seeding
                    </label>
                </span>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="prewarned" value="1" class="userFilter" trigger="click"> Prewarned
                    </label>
                </span>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="hr" value="1" class="userFilter" trigger="click"> H&R
                    </label>
                </span>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="immune" value="1" class="userFilter" trigger="click"> Immune
                    </label>
                </span>
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="completed" value="1" class="userFilter" trigger="click"> Completed
                    </label>
                </span>

                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Sorting</label>
                        <div class="col-sm-2">
                            <select id="sorting" name="sorting" trigger="change" class="form-control userFilter">
                                <option value="name">Name</option>
                                <option value="agent">Agent</option>
                                <option value="active">Active</option>
                                <option value="seeder">Seeder</option>
                                <option value="uploaded">Uploaded</option>
                                <option value="downloaded">Downloaded</option>
                                <option value="seedtime">Seedtime</option>
                                <option value="created_at">Created at</option>
                                <option value="updated_at">Updated at</option>
                                <option value="completed_at">Completed at</option>
                                <option value="prewarn">Prewarned</option>
                                <option value="hitrun">H&R</option>
                                <option value="immune">Immune</option>
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Direction</label>
                        <div class="col-sm-2">
                            <select id="direction" name="direction" trigger="change" class="form-control userFilter">
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
                <span id="filterHeader"></span>
                <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}">
        <div class="block">
            <!-- History -->
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <div class="head"><strong>@lang('user.torrents-history')</strong></div>
                    <thead>
                    <th>@lang('torrent.name')</th>
                    <th>@lang('torrent.agent')</th>
                    <th>@lang('common.active')</th>
                    <th>@lang('torrent.seeder')</th>
                    <th>@lang('torrent.uploaded')</th>
                    <th>@lang('torrent.downloaded')</th>
                    <th>@lang('torrent.seedtime')</th>
                    <th>@lang('torrent.created_at')</th>
                    <th>@lang('torrent.updated_at')</th>
                    <th>@lang('torrent.completed_at')</th>
                    <th>@lang('torrent.prewarn')</th>
                    <th>@lang('torrent.hitrun')</th>
                    <th>@lang('torrent.immune')</th>
                    </thead>
                    <tbody id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}">
                    @foreach ($history as $his)
                        <tr class="userFiltered" active="{{ ($his->active ? '1' : '0') }}" seeding="{{ ($his->seeder == 1 ? '1' : '0') }}" prewarned="{{ ($his->prewarn ? '1' : '0') }}" hr="{{ ($his->hitrun ? '1' : '0') }}" immune="{{ ($his->immune ? '1' : '0') }}">
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
        </div>
            </div>
@endsection
