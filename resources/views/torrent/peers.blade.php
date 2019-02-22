@extends('layout.default')

@section('title')
    <title>@lang('torrent.peers') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('torrent.peers')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrent')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.peers')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title">@lang('torrent.torrent') @lang('torrent.peers')</h1>
        <div class="block">
            <div class="">
                <p class="lead">@lang('torrent.peers') {{ strtolower(trans('common.for')) }}
                    <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">{{ $torrent->name }}</a>
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th>@lang('common.user')</th>
                    <th>@lang('torrent.progress')</th>
                    <th>@lang('torrent.uploaded')</th>
                    <th>@lang('torrent.downloaded')</th>
                    <th>@lang('torrent.left')</th>
                    <th>@lang('torrent.client')</th>
                    <th>@lang('common.ip')</th>
                    <th>@lang('common.port')</th>
                    <th>@lang('torrent.started')</th>
                    <th>@lang('torrent.last-update')</th>
                    <th>@lang('common.status')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($peers as $p)
                    <tr>
                        @if ($p->user->hidden == 1 || $p->user->peer_hidden == 1 || !auth()->user()->isAllowed($p->user,'torrent','show_peer'))
                            <td>
                                <span class="badge-user text-orange text-bold"><i class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                                                  aria-hidden="true"></i>{{ strtoupper(trans('common.anonymous')) }}</span>
                                @if (auth()->user()->id == $p->id || auth()->user()->group->is_modo)
                                    <a href="{{ route('profile', ['username' => $p->user->username, 'id' => $p->user->id]) }}"><span
                                                class="badge-user text-bold" style="color:{{ $p->user->group->color }}">({{ $p->user->username }}
                                            )</span></a>@endif</td>
                        @else
                            <td>
                                @if($p->user->privacy && $p->user->privacy->show_peer != 1)
                                    <span class="badge-user text-orange text-bold"><i class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                                                      aria-hidden="true"></i>{{ strtoupper(trans('common.anonymous')) }}</span>
                                @endif
                                <a href="{{ route('profile', ['username' => $p->user->username, 'id' => $p->user->id]) }}"><span
                                            class="badge-user text-bold"
                                            style="color:{{ $p->user->group->color }}; background-image:{{ $p->user->group->effect }};"><i
                                                class="{{ $p->user->group->icon }}" data-toggle="tooltip"
                                                data-original-title="{{ $p->user->group->name }}"></i> {{ $p->user->username }}</span></a>
                            </td>
                        @endif
                        @if ($p->seeder == 0)
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                         aria-valuenow="{{ $p->downloaded / $torrent->size * 100 }}" aria-valuemin="0"
                                         aria-valuemax="100"
                                         style="width: {{ $p->downloaded / $torrent->size * 100 }}%;">
                                        {{ round($p->downloaded / $torrent->size * 100) }}%
                                    </div>
                                </div>
                            </td>
                        @elseif ($p->seeder == 1)
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                        100%
                                    </div>
                                </div>
                            </td>
                        @endif
                        <td>
                            <span class="badge-extra text-green text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->uploaded, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-extra text-red text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->downloaded, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
                        </td>
                        <td><span class="badge-extra text-purple text-bold">{{ $p->agent }}</span></td>
                        @if (auth()->user()->group->is_modo)
                            <td><span class="badge-extra text-bold">{{ $p->ip }}</span></td>
                            <td><span class="badge-extra text-bold">{{ $p->port }}</span></td>
                        @else
                            <td> ---</td>
                            <td> ---</td>
                        @endif
                        <td>{{ $p->created_at ? $p->created_at->diffForHumans() : 'N/A' }}</td>
                        <td>{{ $p->updated_at ? $p->updated_at->diffForHumans() : 'N/A' }}</td>
                        <td> @if ($p->seeder == 0)
                                <span class='label label-danger'>{{ strtoupper(trans('torrent.leecher')) }}</span>
                            @elseif ($p->seeder == 1)
                                <span class='label label-success'>{{ strtoupper(trans('torrent.seeder')) }}</span>
                            @else
                                <span class='label label-warning'>{{ strtoupper(trans('common.error')) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
            <div class="text-center">
            {{ $peers->links() }}
            </div>
        </div>
    </div>
@endsection
