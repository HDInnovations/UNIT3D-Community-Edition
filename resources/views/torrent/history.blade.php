@extends('layout.default')

@section('title')
    <title>{{ __('torrent.history') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('torrent.history') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('torrent', ['id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('torrent.torrent') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('history', ['id' => $torrent->id]) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('torrent.history') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="title">{{ __('torrent.torrent') }} {{ __('torrent.history') }}</h1>
        <div class="block">
            <p class="lead">{{ __('torrent.history') }} {{ __('common.for') }}
                <a href="{{ route('torrent', ['id' => $torrent->id]) }}">{{ $torrent->name }}</a>
                <span class="badge-extra pull-right">Total Up: {{ App\Helpers\StringHelper::formatBytes($history->sum('actual_uploaded'), 2) }}</span>
                <span class="badge-extra pull-right">Total Down: {{ App\Helpers\StringHelper::formatBytes($history->sum('actual_downloaded'), 2) }}</span>
            </p>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>{{ __('common.user') }}</th>
                        <th>{{ __('common.connected') }}</th>
                        <th>{{ __('torrent.completed') }}</th>
                        <th>{{ __('common.upload') }}</th>
                        <th>{{ __('common.download') }}</th>
                        <th>{{ __('common.added') }}</th>
                        <th>{{ __('torrent.last-update') }}</th>
                        <th>{{ __('torrent.seedtime') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($history as $hpeers)
                        <tr>
                            @if ($hpeers->user->hidden == 1 || $hpeers->user->peer_hidden == 1 ||
                                !auth()->user()->isAllowed($hpeers->user,'torrent','show_peer'))
                                <td>
                                        <span class="badge-user text-orange text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                    aria-hidden="true"></i>{{ strtoupper(__('common.anonymous')) }}</span>
                                    @if (auth()->user()->id == $hpeers->user->id || auth()->user()->group->is_modo)
                                        <a href="{{ route('users.show', ['username' => $hpeers->user->username]) }}"><span
                                                    class="badge-user text-bold"
                                                    style="color:{{ $hpeers->user->group->color }};">{{ $hpeers->user->username }}</span></a>
                                    @endif
                                </td>
                            @else
                                <td>

                                    @if($hpeers->user->privacy && $hpeers->user->privacy->show_peer != 1)
                                        <span class="badge-user text-orange text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                    aria-hidden="true"></i>{{ strtoupper(__('common.anonymous')) }}</span>
                                    @endif
                                    <a href="{{ route('users.show', ['username' => $hpeers->user->username]) }}"><span
                                                class="badge-user text-bold"
                                                style="color:{{ $hpeers->user->group->color }}; background-image:{{ $hpeers->user->group->effect }};"><i
                                                    class="{{ $hpeers->user->group->icon }}" data-toggle="tooltip"
                                                    data-original-title="{{ $hpeers->user->group->name }}"></i>
                                                {{ $hpeers->user->username }}</span></a>
                                </td>
                            @endif
                            @if ($hpeers->active == 1)
                                <td class="text-green">{{ strtolower(__('common.yes')) }}</td> @else
                                <td class="text-red">{{ strtolower(__('common.no')) }}</td> @endif
                            @if ($hpeers->seeder == 1)
                                <td class="text-green">{{ strtolower(__('common.yes')) }}</td> @else
                                <td class="text-red">{{ strtolower(__('common.no')) }}</td> @endif
                            <td>
                                    <span
                                            class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_uploaded, 2) }}</span>
                                <span class="badge-extra text-blue" data-toggle="tooltip"
                                      data-original-title="{{ __('torrent.credited') }} {{ strtolower(__('common.upload')) }}">{{ App\Helpers\StringHelper::formatBytes($hpeers->uploaded, 2) }}</span>
                            </td>
                            <td>
                                    <span
                                            class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_downloaded, 2) }}</span>
                                <span class="badge-extra text-orange" data-toggle="tooltip"
                                      data-original-title="{{ __('torrent.credited') }} {{ strtolower(__('common.download')) }}">{{ App\Helpers\StringHelper::formatBytes($hpeers->downloaded, 2) }}</span>
                            </td>
                            <td>{{ $hpeers->created_at ? $hpeers->created_at->diffForHumans() : 'N/A' }}</td>
                            <td>{{ $hpeers->updated_at ? $hpeers->updated_at->diffForHumans() : 'N/A' }}</td>
                            @if ($hpeers->seedtime < config('hitrun.seedtime'))
                                <td>
                                        <span
                                                class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($hpeers->seedtime) }}</span>
                                </td>
                            @else
                                <td>
                                            <span
                                                    class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($hpeers->seedtime) }}</span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
