@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.requested') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_requested', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.requested') }}</span>
        </a>
    </li>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.other')
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
                    @if (config('hitrun.enabled') == true)
                        <div class="mx-0 mt-5 form-group fatten-me">
                            <label for="name"
                                   class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.status') }}</label>
                            <div class="col-sm-10">
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="unfilled" value="1" class="userFilter"
                                               trigger="click">
                                        <span class="{{ config('other.font-awesome') }} fa-times-circle text-blue"></span>
                                        {{ __('request.unfilled') }}
                                    </label>
                                </span>
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="claimed" value="1" class="userFilter"
                                               trigger="click">
                                        <span class="{{ config('other.font-awesome') }} fa-hand-paper text-blue"></span>
                                        {{ __('request.claimed') }}
                                    </label>
                                </span>
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="pending" value="1" class="userFilter"
                                               trigger="click">
                                        <span class="{{ config('other.font-awesome') }} fa-question-circle text-blue"></span>
                                        {{ __('request.pending') }}
                                    </label>
                                </span>
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="filled" value="1" class="userFilter" trigger="click">
                                        <span class="{{ config('other.font-awesome') }} fa-check-circle text-blue"></span>
                                        {{ __('request.filled') }}
                                    </label>
                                </span>
                            </div>
                        </div>
                    @endif
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="qty"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('common.sort') }}</label>
                        <div class="col-sm-2">
                            <label for="sorting"></label><select id="sorting" name="sorting" trigger="change"
                                                                 class="form-control userFilter">
                                <option value="date">{{ __('bon.date') }}</option>
                                <option value="name">{{ __('torrent.name') }}</option>
                                <option value="bounty">{{ __('request.bounty') }}</option>
                                <option value="votes">{{ __('request.votes') }}</option>
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
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="requests">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th class="torrents-icon">{{ __('torrent.category') }}</th>
                        <th>{{ __('torrent.type') }}</th>
                        <th class="torrents-filename col-sm-6">{{ __('request.request') }}</th>
                        <th>{{ __('request.votes') }}</th>
                        <th>{{ __('common.comments') }}</th>
                        <th>{{ __('request.bounty') }}</th>
                        <th>{{ __('request.age') }}</th>
                        <th>{{ __('request.claimed') }} / {{ __('request.filled') }}</th>
                        </thead>
                        <tbody>
                        @foreach ($torrentRequests as $torrentRequest)
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <i class="{{ $torrentRequest->category->icon }} torrent-icon"
                                           data-toggle="tooltip"
                                           data-original-title="{{ $torrentRequest->category->name }} {{ __('request.request') }}"></i>
                                    </div>
                                </td>
                                <td>
                                        <span class="label label-success">
                                            {{ $torrentRequest->type->name }}
                                        </span>
                                </td>
                                <td>
                                    <a class="view-torrent"
                                       href="{{ route('request', ['id' => $torrentRequest->id]) }}">
                                        {{ $torrentRequest->name }}
                                    </a>
                                </td>
                                <td>
                                        <span class="badge-user">
                                            {{ $torrentRequest->votes }}
                                        </span>
                                </td>
                                <td>
                                        <span class="badge-user">
                                            {{ $torrentRequest->comments->count() }}
                                        </span>
                                </td>
                                <td>
                                        <span class="badge-user">
                                            {{ $torrentRequest->bounty }}
                                        </span>
                                </td>
                                <td>
                                        <span>
                                            {{ $torrentRequest->created_at->diffForHumans() }}
                                        </span>
                                </td>
                                <td>
                                    @if ($torrentRequest->claimed != null && $torrentRequest->filled_hash == null)
                                        <button class="btn btn-xs btn-primary">
                                            <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i>
                                            {{ __('request.claimed') }}
                                        </button>
                                    @elseif ($torrentRequest->filled_hash != null && $torrentRequest->approved_by == null)
                                        <button class="btn btn-xs btn-info">
                                            <i class="{{ config('other.font-awesome') }} fa-question-circle"></i>
                                            {{ __('request.pending') }}
                                        </button>
                                    @elseif ($torrentRequest->filled_hash == null)
                                        <button class="btn btn-xs btn-danger">
                                            <i class="{{ config('other.font-awesome') }} fa-times-circle"></i>
                                            {{ __('request.unfilled') }}
                                        </button>
                                    @else
                                        <button class="btn btn-xs btn-success">
                                            <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                                            {{ __('request.filled') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $torrentRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
