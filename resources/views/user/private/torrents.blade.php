@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_torrents', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.torrents') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.stats')
            <div class="button-holder some-padding">
                <div class="button-left">

                </div>
                <div class="button-right">
                    <span class="badge-user"><strong>{{ __('user.total-download') }}:</strong>
                        <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his_downl, 2) }}</span>
                        <span class="badge-extra text-orange" data-toggle="tooltip"
                              data-original-title="{{ __('user.credited-download') }}">{{ App\Helpers\StringHelper::formatBytes($his_downl_cre, 2) }}</span>
                    </span>
                    <span class="badge-user"><strong>{{ __('user.total-upload') }}:</strong>
                        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his_upl, 2) }}</span>
                        <span class="badge-extra text-blue" data-toggle="tooltip"
                              data-original-title="{{ __('user.credited-upload') }}">{{ App\Helpers\StringHelper::formatBytes($his_upl_cre, 2) }}</span>
                    </span>
                </div>
            </div>
            <hr class="some-padding">
            <div class="container well search mt-5">
                <div class="form-horizontal form-condensed form-torrent-search form-bordered">
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.name') }}</label>
                        <div class="col-sm-9 fatten-me">
                            <label for="search"></label><input type="text" class="form-control userFilter"
                                                               trigger="keyup"
                                                               id="search" placeholder="{{ __('torrent.name') }}">
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.filters') }}</label>
                        <div class="col-sm-10">
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="active" value="1" class="userFilter" trigger="click">
                                    {{ __('common.connected') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="seeding" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.seeding') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="prewarned" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.prewarn') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="hr" value="1" class="userFilter" trigger="click"> H&R
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="immune" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.immune') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="completed" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.completed') }}
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.sort') }}</label>
                        <div class="col-sm-2">
                            <label for="sorting"></label><select id="sorting" name="sorting" trigger="change"
                                                                 class="form-control userFilter">
                                <option value="created_at">{{ __('torrent.created_at') }}</option>
                                <option value="name">{{ __('torrent.name') }}</option>
                                <option value="agent">{{ __('torrent.client') }}</option>
                                <option value="active">{{ __('common.connected') }}</option>
                                <option value="seeder">{{ __('torrent.seeder') }}</option>
                                <option value="uploaded">{{ __('common.upload') }}</option>
                                <option value="downloaded">{{ __('common.download') }}</option>
                                <option value="seedtime">{{ __('torrent.seedtime') }}</option>
                                <option value="updated_at">{{ __('torrent.updated_at') }}</option>
                                <option value="completed_at">{{ __('torrent.completed_at') }}</option>
                                <option value="prewarn">{{ __('torrent.prewarn') }}</option>
                                <option value="hitrun">{{ __('torrent.hitrun') }}</option>
                                <option value="immune">{{ __('torrent.immune') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.direction') }}</label>
                        <div class="col-sm-2">
                            <label for="direction"></label><select id="direction" name="direction" trigger="change"
                                                                   class="form-control userFilter">
                                <option value="desc">{{ __('common.descending') }}</option>
                                <option value="asc">{{ __('common.ascending') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <span id="filterHeader"></span>
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="history">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th>{{ __('torrent.name') }}</th>
                        <th>{{ __('torrent.client') }}</th>
                        <th>{{ __('common.connected') }}</th>
                        <th>{{ __('torrent.seeder') }}</th>
                        <th>{{ __('common.upload') }}</th>
                        <th>{{ __('common.download') }}</th>
                        <th>{{ __('torrent.seedtime') }}</th>
                        <th>{{ __('torrent.created_at') }}</th>
                        <th>{{ __('torrent.updated_at') }}</th>
                        <th>{{ __('torrent.completed_at') }}</th>
                        <th>{{ __('torrent.prewarn') }}</th>
                        <th>{{ __('torrent.hitrun') }}</th>
                        <th>{{ __('torrent.immune') }}</th>
                        </thead>
                        <tbody>
                        @foreach ($history as $his)
                            <tr class="userFiltered" active="{{ $his->active ? '1' : '0' }}"
                                seeding="{{ $his->seeder == 1 ? '1' : '0' }}"
                                prewarned="{{ $his->prewarn ? '1' : '0' }}"
                                hr="{{ $his->hitrun ? '1' : '0' }}" immune="{{ $his->immune ? '1' : '0' }}">
                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['id' => $his->torrent->id]) }}">
                                        {{ $his->torrent->name }}
                                    </a>
                                </td>
                                <td>
                                        <span
                                                class="badge-extra text-purple">{{ $his->agent ?: __('common.unknown') }}</span>
                                </td>
                                @if ($his->active == 1)
                                    <td class="text-green">{{ __('common.yes') }}</td> @else
                                    <td class="text-red">{{ __('common.no') }}</td> @endif
                                @if ($his->seeder == 1)
                                    <td class="text-green">{{ __('common.yes') }}</td> @else
                                    <td class="text-red">{{ __('common.no') }}</td> @endif
                                <td>
                                        <span
                                                class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his->actual_uploaded, 2) }}</span>
                                    <span class="badge-extra text-blue" data-toggle="tooltip"
                                          data-original-title="{{ __('user.credited-upload') }}">{{ App\Helpers\StringHelper::formatBytes($his->uploaded, 2) }}</span>
                                </td>
                                <td>
                                        <span
                                                class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his->actual_downloaded, 2) }}</span>
                                    <span class="badge-extra text-orange" data-toggle="tooltip"
                                          data-original-title="{{ __('user.credited-download') }}">{{ App\Helpers\StringHelper::formatBytes($his->downloaded, 2) }}</span>
                                </td>
                                @if ($his->seedtime < config('hitrun.seedtime'))
                                    <td>
                                            <span
                                                    class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                                    </td>
                                @else
                                    <td>
                                                <span
                                                        class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span>
                                    </td>
                                @endif
                                <td>{{ $his->created_at && $his->created_at != null ? $his->created_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td>{{ $his->updated_at && $his->updated_at != null ? $his->updated_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td>{{ $his->completed_at && $his->completed_at != null ? $his->completed_at->diffForHumans() : 'N/A' }}
                                </td>
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
                        {{ $history->links() }}
                    </div>
                </div>


            </div>
        </div>
@endsection
