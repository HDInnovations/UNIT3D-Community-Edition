@extends('layout.default')

@section('title')
    <title>@lang('torrent.download-check') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('torrent', ['id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('download_check', ['id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.download-check')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="text-center">
                        <h2>{{ $user->username }}</h2>
                        @if ($user->getRatio() < config('other.ratio') || $user->can_download == 0)
                                <h4>@lang('torrent.no-privileges')</h4>
                            @else
                                <h4>@lang('torrent.ready')</h4>
                            @endif
                    </div>
                </div>
            </div>
    
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="text-center">@lang('torrent.info')</h4>
                </div>
                <div class="text-center">
                    <h3 class="movie-title">
                        <a href="{{ route('torrent', ['id' => $torrent->id]) }}"
                            title="{{ $torrent->name }}">{{ $torrent->name }}</a>
                    </h3>
                    <ul class="list-inline">
                        <span class="badge-extra text-blue"><i class="{{ config('other.font-awesome') }} fa-database"></i>
                            <strong>@lang('torrent.size')
                                : </strong> {{ $torrent->getSize() }}</span>
                        <span class="badge-extra text-blue"><i
                                class="{{ config('other.font-awesome') }} fa-fw fa-clock"></i>
                            <strong>@lang('torrent.released')
                                : </strong> {{ $torrent->created_at->diffForHumans() }}</span>
                        <span class="badge-extra text-green">
                            <li><i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                <strong>@lang('torrent.seeders')
                                    : </strong> {{ $torrent->seeders }}</li>
                        </span>
                        <span class="badge-extra text-red">
                            <li><i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                <strong>@lang('torrent.leechers')
                                    : </strong> {{ $torrent->leechers }}</li>
                        </span>
                        <span class="badge-extra text-orange">
                            <li><i class="{{ config('other.font-awesome') }} fa-check-square"></i>
                                <strong>@lang('torrent.completed')
                                    : </strong> {{ $torrent->times_completed }}</li>
                        </span>
                    </ul>
                </div>
            </div>
        </div>
        <div class="well">
            <div class="text-center">
                <strong>@lang('common.ratio') {{ strtolower(trans('torrent.greater-than')) }} {{ config('other.ratio') }}
                    : </strong>
                @if ($user->getRatio() < config('other.ratio'))<span class="badge-extra text-red"><i
                            class="{{ config('other.font-awesome') }} fa-times"></i>
                        {{ strtoupper(trans('torrent.failed')) }}</span>
                    @else<span class="badge-extra text-green"><i class="{{ config('other.font-awesome') }} fa-check"></i>
                            {{ strtoupper(trans('torrent.passed')) }}</span>
                    @endif
                    <strong>@lang('torrent.download-rights-active'): </strong>
                    @if ($user->can_download == 0 && $torrent->user_id != $user->id)<span class="badge-extra text-red"><i
                                class="{{ config('other.font-awesome') }} fa-times"></i>
                            {{ strtoupper(trans('torrent.failed')) }}</span>
                    @else<span class="badge-extra text-green"><i class="{{ config('other.font-awesome') }} fa-check"></i>
                            {{ strtoupper(trans('torrent.passed')) }}</span>
                    @endif
                    <strong>@lang('torrent.moderation'): </strong>
                    @if ($torrent->isRejected())<span class="badge-extra text-red"><i
                                class="{{ config('other.font-awesome') }} fa-times"></i>
                            {{ strtoupper(trans('torrent.rejected')) }}</span>
                    @elseif ($torrent->isPending())<span class="badge-extra text-orange"><i
                                class="{{ config('other.font-awesome') }} fa-times"></i>
                            {{ strtoupper(trans('torrent.pending')) }}</span>
                    @else<span class="badge-extra text-green"><i class="{{ config('other.font-awesome') }} fa-check"></i>
                            {{ strtoupper(trans('torrent.approved')) }}</span>
                    @endif
            </div>
            <br>
            <div class="text-center">
                @if ($user->getRatio() < config('other.ratio') || $user->can_download == 0 && $torrent->user_id !=
                        $user->id)
                        <span class="text-red text-bold">@lang('torrent.no-privileges-desc')</span>
                    @else
                        <a href="{{ route('download', ['id' => $torrent->id]) }}" role="button"
                            class="btn btn-labeled btn-primary">
                            <span class='btn-label'><i
                                    class='{{ config('other.font-awesome') }} fa-download'></i></span>@lang('common.download')</a>
                    @endif
            </div>
        </div>
    </div>
@endsection
