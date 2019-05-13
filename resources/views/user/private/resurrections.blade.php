@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.resurrections') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('user_profile', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_resurrections', ['slug' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.resurrections')</span>
        </a>
    </li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.other')
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        {{ $user->username }} @lang('user.resurrections')
                    </h1>
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
                            <label for="name" class="mt-5 col-sm-1 label label-default fatten-me">Status</label>
                            <div class="col-sm-10">
                        <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="rewarded" value="1" class="userFilter" trigger="click"> Rewarded
                    </label>
                </span>
                                <span class="badge-user">
                    <label class="inline">
                        <input type="checkbox" id="notrewarded" value="1" class="userFilter" trigger="click"> Not Rewarded
                    </label>
                </span>
                            </div>
                        </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Sorting</label>
                        <div class="col-sm-2">
                            <select id="sorting" name="sorting" trigger="change" class="form-control userFilter">
                                <option value="created_at">Resurrect Date</option>
                                <option value="name">Name</option>
                                <option value="size">Size</option>
                                <option value="seeders">Seeders</option>
                                <option value="leechers">Leechers</option>
                                <option value="times_completed">Times Completed</option>
                                <option value="goal">Seedtime Goal</option>
                            </select>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty" class="mt-5 col-sm-1 label label-default fatten-me">Direction</label>
                        <div class="col-sm-2">
                            <select id="direction" name="direction" trigger="change" class="form-control userFilter">
                                <option value="desc">@lang('common.descending')</option>
                                <option value="asc">@lang('common.ascending')</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <span id="filterHeader"></span>
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="resurrections">
                <!-- Resurrections -->
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th>@lang('torrent.name')</th>
                        <th>@lang('torrent.size')</th>
                        <th>@lang('torrent.seeders')</th>
                        <th>@lang('torrent.leechers')</th>
                        <th>@lang('torrent.completed')</th>
                        <th>Resurrect Date</th>
                        <th>Current Seedtime</th>
                        <th>Seedtime Goal</th>
                        <th>Rewarded</th>
                        <th>Cancel</th>
                        </thead>
                        <tbody>
                        @foreach ($resurrections as $resurrection)
                            <tr>
                                <td>
                                    <div class="torrent-file">
                                        <div>
                                            <a class="view-torrent" href="{{ route('torrent', ['slug' => $resurrection->torrent->slug, 'id' => $resurrection->torrent->id]) }}">
                                                {{ $resurrection->torrent->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-extra text-blue text-bold"> {{ $resurrection->torrent->getSize() }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-green text-bold"> {{ $resurrection->torrent->seeders }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-red text-bold"> {{ $resurrection->torrent->leechers }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-orange text-bold"> {{ $resurrection->torrent->times_completed }} @lang('common.times')</span>
                                </td>
                                <td>
                                    {{ $resurrection->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    @php $torrent = App\Models\Torrent::where('id', '=', $resurrection->torrent_id)->pluck('info_hash'); @endphp
                                    @php $history = App\Models\History::select(['seedtime'])->where('user_id', '=', $user->id)->where('info_hash', '=', $torrent)->first(); @endphp
                                    {{ (empty($history)) ? '0' : App\Helpers\StringHelper::timeElapsed($history->seedtime) }}
                                </td>
                                <td>
                                    {{ App\Helpers\StringHelper::timeElapsed($resurrection->seedtime) }}
                                </td>
                                <td>
                                    @if ($resurrection->rewarded == 0)
                                        <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                    @else
                                        <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('graveyard.destroy', ['id' => $resurrection->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" @if ($resurrection->rewarded == 1) disabled @endif>
                                            <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $resurrections->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
