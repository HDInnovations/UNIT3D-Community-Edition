@extends('layout.default')

@section('title')
    <title>{{ trans('torrent.download-check') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('torrents') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrents') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $torrent->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.download-check') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div align="center">
                        <h2>{{ $user->username }}</h2>
                        @if($user->getRatio() < config('other.ratio') || $user->can_download == 0)
                            <h4>{{ trans('torrent.no-privileges') }}</h4>
                        @else
                            <h4>{{ trans('torrent.ready') }}</h4>
                        @endif
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="text-center">{{ trans('torrent.info') }}</h4></div>
                <div class="text-center">
                    <h3 class="movie-title">
                        <a href="#" title="{{ $torrent->name }}">{{ $torrent->name }}</a>
                    </h3>
                    <ul class="list-inline">
                        <span class="badge-extra text-blue"><i class="fa fa-database"></i> <strong>{{ trans('torrent.size') }}
                                : </strong> {{ $torrent->getSize() }}</span>
                        <span class="badge-extra text-blue"><i class="fa fa-fw fa-calendar"></i> <strong>{{ trans('torrent.released') }}
                                : </strong> {{ $torrent->created_at->diffForHumans() }}</span>
                        <span class="badge-extra text-green"><li><i class="fa fa-arrow-up"></i> <strong>{{ trans('torrent.seeders') }}
                                    : </strong> {{ $torrent->seeders }}</li></span>
                        <span class="badge-extra text-red"><li><i class="fa fa-arrow-down"></i> <strong>{{ trans('torrent.leechers') }}
                                    : </strong> {{ $torrent->leechers }}</li></span>
                        <span class="badge-extra text-orange"><li><i class="fa fa-check-square-o"></i> <strong>{{ trans('torrent.completed') }}
                                    : </strong> {{ $torrent->times_completed }}</li></span>
                    </ul>
                </div>
            </div>
        </div>
        <div class="well">
            <div class="text-center">
                <strong>{{ trans('common.ratio') }} {{ strtolower(trans('torrent.greater-than')) }} {{ config('other.ratio') }}
                    : </strong>
                @if($user->getRatio() < config('other.ratio'))<span class="badge-extra text-red"><i
                            class="fa fa-times"></i> {{ strtoupper(trans('torrent.failed')) }}</span>
                @else<span class="badge-extra text-green"><i
                            class="fa fa-check"></i> {{ strtoupper(trans('torrent.passed')) }}</span>
                @endif
                <strong>Download Rights Active: </strong>
                @if($user->can_download == 0 && $torrent->user_id != $user->id)<span class="badge-extra text-red"><i
                            class="fa fa-times"></i> {{ strtoupper(trans('torrent.failed')) }}</span>
                @else<span class="badge-extra text-green"><i
                            class="fa fa-check"></i> {{ strtoupper(trans('torrent.passed')) }}</span>
                @endif
                <strong>Torrent Status: </strong>
                @if($torrent->isRejected())<span class="badge-extra text-red"><i
                            class="fa fa-times"></i> {{ strtoupper(trans('torrent.rejected')) }}</span>
                @elseif($torrent->isPending())<span class="badge-extra text-orange"><i
                            class="fa fa-times"></i> {{ strtoupper(trans('torrent.pending')) }}</span>
                @else<span class="badge-extra text-green"><i
                            class="fa fa-check"></i> {{ strtoupper(trans('torrent.approved')) }}</span>
                @endif
            </div>
            <br>
            <div class="text-center">
                @if($user->getRatio() < config('other.ratio') || $user->can_download == 0 && $torrent->user_id != $user->id)
                    <span class="text-red text-bold">{{ trans('torrent.no-privileges-desc') }}</span>
                @else
                    <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}"
                       role="button" class="btn btn-labeled btn-primary">
                        <span class='btn-label'><i class='fa fa-download'></i></span>{{ trans('common.download') }}</a>
                @endif
            </div>
        </div>
    </div>
@endsection