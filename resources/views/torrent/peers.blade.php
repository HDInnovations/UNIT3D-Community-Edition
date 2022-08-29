@extends('layout.default')

@section('title')
    <title>{{ __('torrent.peers') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('torrent.peers') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('torrent', ['id' => $torrent->id]) }}" class="breadcrumb__link">
            {{ $torrent->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('torrent.peers') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title">{{ __('torrent.torrent') }} {{ __('torrent.peers') }}</h1>
        <div class="block">
            <div class="">
                <p class="lead">{{ __('torrent.peers') }} {{ strtolower(__('common.for')) }}
                    <a href="{{ route('torrent', ['id' => $torrent->id]) }}">{{ $torrent->name }}</a>
                </p>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('torrent.progress') }}</th>
                        <th>{{ __('common.upload') }}</th>
                        <th>{{ __('common.download') }}</th>
                        <th>{{ __('torrent.left') }}</th>
                        <th>{{ __('torrent.client') }}</th>
                        <th>{{ __('common.ip') }}</th>
                        <th>{{ __('common.port') }}</th>
                        @if (\config('announce.connectable_check') == true)
                        <th>Connectable</th>
                        @endif
                        <th>{{ __('torrent.started') }}</th>
                        <th>{{ __('torrent.last-update') }}</th>
                        <th>{{ __('common.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($peers as $p)
                        <tr>
                            @if ($p->user->hidden == 1 || $p->user->peer_hidden == 1 ||
                                !auth()->user()->isAllowed($p->user,'torrent','show_peer') ||
                                ($p->user->id == $torrent->user->id && $torrent->anon == 1))
                                <td>
                                        <span class="badge-user text-orange text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                    aria-hidden="true"></i>{{ strtoupper(__('common.anonymous')) }}</span>
                                    @if (auth()->user()->id == $p->user->id || auth()->user()->group->is_modo)
                                        <a href="{{ route('users.show', ['username' => $p->user->username]) }}"><span
                                                    class="badge-user text-bold"
                                                    style="color:{{ $p->user->group->color }};">({{ $p->user->username }}
                                                )</span></a>@endif</td>
                            @else
                                <td>
                                    @if($p->user->privacy && $p->user->privacy->show_peer != 1)
                                        <span class="badge-user text-orange text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                    aria-hidden="true"></i>{{ strtoupper(__('common.anonymous')) }}</span>
                                    @endif
                                    <a href="{{ route('users.show', ['username' => $p->user->username]) }}"><span
                                                class="badge-user text-bold"
                                                style="color:{{ $p->user->group->color }}; background-image:{{ $p->user->group->effect }};"><i
                                                    class="{{ $p->user->group->icon }}" data-toggle="tooltip"
                                                    data-original-title="{{ $p->user->group->name }}"></i>
                                                {{ $p->user->username }}</span></a>
                                </td>
                            @endif
                            <td>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                            aria-valuenow="{{ $p->progress }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="width: {{ $p->progress }}%;">
                                        {{ $p->progress }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                    <span
                                            class="badge-extra text-green text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->uploaded, 2) }}</span>
                            </td>
                            <td>
                                    <span
                                            class="badge-extra text-red text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->downloaded, 2) }}</span>
                            </td>
                            <td>
                                    <span
                                            class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
                            </td>
                            <td><span class="badge-extra text-purple text-bold">{{ $p->agent }}</span></td>
                            @if (auth()->user()->group->is_modo || auth()->user()->id == $p->user_id)
                                <td><span class="badge-extra text-bold">{{ $p->ip }}</span></td>
                                <td><span class="badge-extra text-bold">{{ $p->port }}</span></td>
                            @else
                                <td> ---</td>
                                <td> ---</td>
                            @endif
                            @if (\config('announce.connectable_check') == true)
                                @php
                                    $connectable = false;
                                    if (cache()->has('peers:connectable:'.$p->ip.'-'.$p->port.'-'.$p->agent)) {
                                        $connectable = cache()->get('peers:connectable:'.$p->ip.'-'.$p->port.'-'.$p->agent);
                                    }
                                @endphp
                                <td><span class="badge-extra text-bold {{ $connectable ? 'text-success' : 'text-danger' }}">@choice('user.client-connectable-state', $connectable)</span></td>
                            @endif
                            <td>{{ $p->created_at ? $p->created_at->diffForHumans() : 'N/A' }}</td>
                            <td>{{ $p->updated_at ? $p->updated_at->diffForHumans() : 'N/A' }}</td>
                            <td> @if ($p->seeder == 0)
                                    <span class='label label-danger'>{{ strtoupper(__('torrent.leecher')) }}</span>
                                @elseif ($p->seeder == 1)
                                    <span class='label label-success'>{{ strtoupper(__('torrent.seeder')) }}</span>
                                @else
                                    <span class='label label-warning'>{{ strtoupper(__('common.error')) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
