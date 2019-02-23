@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.unsatisfieds') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('user_profile', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_unsatisfieds', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.unsatisfieds')</span>
        </a>
    </li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.stats')
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        {{ $user->username }} @lang('user.unsatisfieds')
                    </h1>
                </div>
            </div>
            <div class="button-holder some-padding">
                <div class="button-left">

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
            <hr class="some-padding">
            <div class="container well search mt-5">
                <form role="form" method="GET" action="UserController@myFilters" class="form-horizontal form-condensed form-torrent-search form-bordered">
                    @csrf
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">@lang('torrent.name')</label>
                        <div class="col-sm-9 fatten-me">
                            <input type="text" class="form-control userFilter" trigger="keyup" id="search" placeholder="@lang('torrent.name')">
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">Seeding</label>
                        <div class="col-sm-10">
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="seeding" value="1" class="userFilter" trigger="click"> Yes
                    </label>
                </span>
                            <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="error" value="1" class="userFilter" trigger="click"> No
                    </label>
                </span>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Sorting</label>
                        <div class="col-sm-2">
                            <select id="sorting" name="sorting" trigger="change" class="form-control userFilter">
                                <option value="satisfied_at">Satisfied in</option>
                                <option value="completed_at">Completed at</option>
                                <option value="name">Name</option>
                                <option value="seeder">Seeding</option>
                                <option value="size">Size</option>
                                <option value="seeders">Seeders</option>
                                <option value="leechers">Leechers</option>
                                <option value="times_completed">Times Completed</option>
                                <option value="seedtime">Seedtime</option>
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Direction</label>
                        <div class="col-sm-2">
                            <select id="direction" name="direction" trigger="change" class="form-control userFilter">
                                <option value="desc">Descending</option>
                                <option value="asc" selected>Ascending</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <span id="filterHeader"></span>
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="unsatisfieds">
                <!-- Unsatisfieds -->
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th>@lang('torrent.name')</th>
                        <th>@lang('torrent.category')</th>
                        <th>@lang('torrent.size')</th>
                        <th>@lang('torrent.seeders')</th>
                        <th>@lang('torrent.leechers')</th>
                        <th>@lang('torrent.completed')</th>
                        <th>@lang('torrent.completed_at')</th>
                        <th>@lang('torrent.satisfied_in')</th>
                        <th>@lang('torrent.seedtime')</th>
                        <th>@lang('torrent.status')</th>
                        </thead>
                        <tbody>
                        @foreach ($downloads as $download)
                            <tr class="userFiltered" active="{{ ($download->active ? '1' : '0') }}" seeding="{{ ($download->seeder == 1 ? '1' : '0') }}" prewarned="{{ ($download->prewarn ? '1' : '0') }}" hr="{{ ($download->hitrun ? '1' : '0') }}" immune="{{ ($download->immune ? '1' : '0') }}">
                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['slug' => $download->torrent->slug, 'id' => $download->torrent->id]) }}">
                                        {{ $download->torrent->name }}
                                    </a>
                                    <div class="pull-right">
                                        <a href="{{ route('download', ['slug' => $download->torrent->slug, 'id' => $download->torrent->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button"><i
                                                        class="{{ config('other.font-awesome') }} fa-download"></i></button>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('category', ['slug' => $download->torrent->category->slug, 'id' => $download->torrent->category->id]) }}">{{ $download->torrent->category->name }}</a>
                                </td>
                                <td>
                                    <span class="badge-extra text-blue text-bold"> {{ $download->torrent->getSize() }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-green text-bold"> {{ $download->torrent->seeders }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-red text-bold"> {{ $download->torrent->leechers }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-orange text-bold"> {{ $download->torrent->times_completed }} @lang('common.times')</span>
                                </td>
                                <td>{{ $download->completed_at && $download->completed_at != null ? $download->completed_at->diffForHumans() : "N/A"}}</td>
                                @if ($download->seedtime < config('hitrun.seedtime'))
                                    <td>
                                        <span class="badge-extra text-red">{{ App\Helpers\StringHelper::timeRemaining($download->seedtime) }}</span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                                    </td>
                                @endif
                                @if ($download->seedtime < config('hitrun.seedtime'))
                                    <td>
                                        <span class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($download->seedtime) }}</span>
                                    </td>
                                @endif
                                <td>
                                    @if ($download->seeder == 1)
                                        <span class='label label-success'>SEEDING</span>
                                    @else
                                        <span class='label label-danger'>NOT SEEDING</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $downloads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
