@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.active') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_active', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.active') }}</span>
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
                            <label for="search"></label>
                            <input type="text" class="form-control userFilter" trigger="keyup" id="search"
                                   placeholder="{{ __('torrent.name') }}">
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.status') }}</label>
                        <div class="col-sm-10">
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="seeding" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.seeding') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="leeching" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.leeching') }}
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
                                <option value="seeder">{{ __('torrent.seeding') }}</option>
                                <option value="size">{{ __('torrent.size') }}</option>
                                <option value="seeders">{{ __('torrent.seeders') }}</option>
                                <option value="leechers">{{ __('torrent.leechers') }}</option>
                                <option value="uploaded">{{ __('common.upload') }}</option>
                                <option value="downloaded">{{ __('common.download') }}</option>
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
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="active">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th>{{ __('torrent.name') }}</th>
                        <th>{{ __('torrent.client') }}</th>
                        <th>{{ __('torrent.size') }}</th>
                        <th>{{ __('torrent.seeders') }}</th>
                        <th>{{ __('torrent.leechers') }}</th>
                        <th>{{ __('common.upload') }}</th>
                        <th>{{ __('common.download') }}</th>
                        <th>{{ __('torrent.remaining') }}</th>
                        <th>{{ __('torrent.progress') }}</th>
                        </thead>
                        <tbody>
                        @foreach ($active as $p)
                            <tr>
                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['id' => $p->torrent_id]) }}"
                                       data-toggle="tooltip" title="{{ $p->torrent->name }}">
                                        {{ $p->torrent->name }}
                                    </a>
                                </td>
                                <td>
                                        <span
                                                class="badge-extra text-purple text-bold">{{ $p->agent ?: __('common.unknown') }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-blue text-bold"> {{ $p->torrent->getSize() }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-green text-bold"> {{ $p->torrent->seeders }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-red text-bold"> {{ $p->torrent->leechers }}</span>
                                </td>
                                <td>
                                        <span class="badge-extra text-green text-bold">
                                            {{ App\Helpers\StringHelper::formatBytes($p->uploaded, 2) }}</span>
                                </td>
                                <td>
                                        <span class="badge-extra text-red text-bold">
                                            {{ App\Helpers\StringHelper::formatBytes($p->downloaded, 2) }}</span>
                                </td>
                                <td>
                                        <span
                                                class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
                                </td>
                                @if ($p->seeder == 0)
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                 aria-valuenow="{{ ($p->downloaded / $p->torrent->size) * 100 }}"
                                                 aria-valuemin="0" aria-valuemax="100"
                                                 style="width: {{ ($p->downloaded / $p->torrent->size) * 100 }}%;">
                                                {{ round(($p->downloaded / $p->torrent->size) * 100) }}%
                                            </div>
                                        </div>
                                    </td>
                                @elseif ($p->seeder == 1)
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                 aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 100%;">
                                                100%
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $active->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
