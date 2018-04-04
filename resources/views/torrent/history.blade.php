@extends('layout.default')

@section('title')
<title>{{ trans('torrent.history') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ trans('torrent.history') }}">
@endsection

@section('breadcrumb')
<li>
<a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrent') }}</span>
</a>
</li>
<li class="active">
<a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.history') }}</span>
</a>
</li>
@endsection

@section('content')
<div class="container">
<h1 class="title">{{ trans('torrent.torrent') }} {{ trans('torrent.history') }}</h1>
<div class="block">
<div class="">
<p class="lead">{{ trans('torrent.history') }} For
<a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">{{ $torrent->name }}</a>
</p>
</div>
<div class="table-responsive">
<table class="table table-condensed table-striped table-bordered">
<thead>
    <tr>
        <th>{{ trans('common.user') }}</th>
        <th>{{ trans('common.active') }}</th>
        <th>{{ trans('torrent.completed') }}</th>
        <th>{{ trans('common.upload') }}</th>
        <th>{{ trans('common.download') }}</th>
        <th>{{ trans('common.added') }}</th>
        <th>{{ trans('torrent.updated') }}</th>
        <th>{{ trans('torrent.seedtime') }}</th>
    </tr>
</thead>
<tbody>
    @foreach($history as $hpeers)
    <tr>
        @if($hpeers->user->peer_hidden == 1)
        <td>
            <span class="badge-user text-orange text-bold"><i class="fa fa-eye-slash" aria-hidden="true"></i>{{ strtoupper(trans('common.anonymous')) }}</span>
            @if(auth()->user()->id == $hpeers->user->id || auth()->user()->group->is_modo)
            <a href="{{ route('profile', ['username' => $hpeers->user->username, 'id' => $hpeers->user->id]) }}"><span class="badge-user text-bold" style="color:{{ $hpeers->user->group->color }}">{{ $hpeers->user->username }}</span></a>
            @endif
        </td>
        @else
        <td>
            <a href="{{ route('profile', ['username' => $hpeers->user->username, 'id' => $hpeers->user->id]) }}"><span class="badge-user text-bold" style="color:{{ $hpeers->user->group->color }}; background-image:{{ $hpeers->user->group->effect }};"><i class="{{ $hpeers->user->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $hpeers->user->group->name }}"></i> {{ $hpeers->user->username }}</span></a>
        </td>
        @endif
        @if($hpeers->active == 1) <td class="text-green">{{ strtolower(trans('common.yes')) }}</td> @else <td class="text-red">{{ strtolower(trans('common.no')) }}</td> @endif
        @if($hpeers->seeder == 1) <td class="text-green">{{ strtolower(trans('common.yes')) }}</td> @else <td class="text-red">{{ strtolower(trans('common.no')) }}</td> @endif
        <td>
            <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_uploaded,2) }}</span>
            <span class="badge-extra text-blue" data-toggle="tooltip" title="" data-original-title="{{ trans('torrent.credited') }} {{ strtolower(trans('common.upload')) }}">{{ App\Helpers\StringHelper::formatBytes($hpeers->uploaded,2) }}</span>
        </td>
        <td>
            <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_downloaded,2) }}</span>
            <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="{{ trans('torrent.credited') }} {{ strtolower(trans('common.download')) }}">{{ App\Helpers\StringHelper::formatBytes($hpeers->downloaded,2) }}</span>
        </td>
        <td>{{ $hpeers->created_at->diffForHumans() }}</td>
        <td>{{ $hpeers->updated_at->diffForHumans() }}</td>
        @if($hpeers->seedtime < config('hitrun.seedtime'))
          <td><span class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($hpeers->seedtime) }}</span></td>
        @else
          <td><span class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($hpeers->seedtime) }}</span></td>
        @endif
    </tr>
    @endforeach
</tbody>
</table>
{{ $history->links() }}
</div>
</div>
</div>
@endsection
