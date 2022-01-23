@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.uploads') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user_uploads', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ $user->username }} {{ __('user.uploads') }}</span>
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
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.moderation') }}</label>
                        <div class="col-sm-10">
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="pending" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.pending') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="approved" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.approved') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="rejected" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.rejected') }}
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="mx-0 mt-5 form-group fatten-me">
                        <label for="name"
                               class="mt-5 col-sm-1 label label-default fatten-me">{{ __('torrent.filters') }}</label>

                        <div class="col-sm-10">
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="dead" value="1" class="userFilter" trigger="click">
                                    {{ __('graveyard.dead') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="alive" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.alive') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="reseed" value="1" class="userFilter" trigger="click">
                                    {{ __('torrent.requires-reseed') }}
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="error" value="1" class="userFilter" trigger="click">
                                    {{ __('common.error') }}
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
                                <option value="size">{{ __('torrent.size') }}</option>
                                <option value="seeders">{{ __('torrent.seeders') }}</option>
                                <option value="leechers">{{ __('torrent.leechers') }}</option>
                                <option value="times_completed">{{ __('torrent.completed-times') }}</option>
                                <option value="tipped">{{ __('torrent.bon-tipped') }}</option>
                                <option value="thanked">{{ __('torrent.thanked') }}</option>
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
            <div id="userFilter" userName="{{ $user->username }}" userId="{{ $user->id }}" view="uploads">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <th>{{ __('torrent.name') }}</th>
                        <th>{{ __('torrent.category') }}</th>
                        <th>{{ __('torrent.size') }}</th>
                        <th>{{ __('torrent.seeders') }}</th>
                        <th>{{ __('torrent.leechers') }}</th>
                        <th>{{ __('torrent.completed') }}</th>
                        <th>{{ __('torrent.bon-tipped') }}</th>
                        <th>{{ __('torrent.thanked') }}</th>
                        <th>{{ __('torrent.created_at') }}</th>
                        <th>{{ __('torrent.moderation') }}</th>
                        <th>{{ __('torrent.status') }}</th>
                        </thead>
                        <tbody>
                        @foreach ($uploads as $upload)
                            <tr>
                                <td>
                                    <a class="view-torrent" href="{{ route('torrent', ['id' => $upload->id]) }}">
                                        {{ $upload->name }}
                                    </a>
                                    <div class="pull-right">
                                        <a href="{{ route('download', ['id' => $upload->id]) }}">
                                            <button class="btn btn-primary btn-circle" type="button"><i
                                                        class="{{ config('other.font-awesome') }} fa-download"></i>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    {{ $upload->category->name }}
                                </td>
                                <td>
                                    <span class="badge-extra text-blue text-bold"> {{ $upload->getSize() }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-green text-bold"> {{ $upload->seeders }}</span>
                                </td>
                                <td>
                                    <span class="badge-extra text-red text-bold"> {{ $upload->leechers }}</span>
                                </td>
                                <td>
                                        <span class="badge-extra text-orange text-bold"> {{ $upload->times_completed }}
                                            {{ __('common.times') }}</span>
                                </td>
                                <td>
                                        <span class="badge-extra text-green text-bold">
                                            {{ $upload->tipped_total ?: 'N/A' }}</span>
                                </td>
                                <td>
                                        <span class="badge-extra text-red text-bold">
                                            {{ $upload->thanked_total ?: 'N/A' }}</span>
                                </td>
                                <td>{{ $upload->created_at && $upload->created_at != null ? $upload->created_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td>
                                    @if ($upload->isPending())
                                        <span class='label label-warning'
                                              data-toggle="tooltip">{{ __('torrent.pending') }}</span>
                                    @elseif ($upload->isApproved())
                                        <span class='label label-success' data-toggle="tooltip"
                                              data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">{{ __('torrent.approved') }}</span>
                                    @elseif ($upload->isRejected())
                                        <span class='label label-danger' data-toggle="tooltip"
                                              data-original-title="Moderated By {{ $upload->moderated->username }} {{ $upload->moderated_at->diffForHumans() }}">{{ __('torrent.rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($upload->seeders + $upload->leechers == 0)
                                        <span class='label label-danger'>{{ __('graveyard.dead') }}</span>
                                    @elseif ($upload->seeders >= 1)
                                        <span class='label label-success'>{{ __('torrent.alive') }}</span>
                                    @elseif ($upload->leechers >= 1 + $upload->seeders = 0)
                                        <span class='label label-info'>{{ __('torrent.requires-reseed') }}</span>
                                    @else
                                        <span class='label label-warning'>{{ strtoupper(__('common.error')) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $uploads->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
