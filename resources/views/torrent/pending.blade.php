@extends('layout.default')

@section('title')
    <title>{{ __('common.pending-torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('common.pending-torrents') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('common.pending-torrents') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.pending-torrents') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('torrent.name') }}</th>
                        <th>{{ __('torrent.category') }}</th>
                        <th>{{ __('torrent.type') }}</th>
                        <th>{{ __('torrent.resolution') }}</th>
                        <th>{{ __('torrent.size') }}</th>
                        <th>{{ __('torrent.created_at') }}</th>
                        <th>{{ __('torrent.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($torrents as $torrent)
                        <tr>
                            <td>{{ $torrent->name }}</td>
                            <td>{{ $torrent->category->name }}</td>
                            <td>{{ $torrent->type->name }}</td>
                            <td>{{ $torrent->resolution->name ?? 'No Res' }}</td>
                            <td>{{ $torrent->getSize() }}</td>
                            <td>{{ $torrent->created_at->diffForHumans() }}</td>
                            <td>
                                @switch($torrent->status)
                                    @case(\App\Models\Torrent::PENDING)
                                        <span class="torrent--pending">
                                            {{ __('torrent.pending') }}
                                        </span>

                                        @break
                                    @case(\App\Models\Torrent::POSTPONED)
                                        <span class="torrent--postponed">
                                            {{ __('torrent.postponed') }}
                                        </span>

                                        @break
                                    @default
                                        <span class="torrent--rejected">
                                            {{ __('common.unknown') }}
                                        </span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
