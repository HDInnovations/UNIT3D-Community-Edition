@extends('layout.default')

@section('title')
<title>{{ trans('torrent.peers') }} - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ trans('torrent.peers') }}">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.torrent') }}</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('torrent.peers') }}</span>
  </a>
</li>
@stop

@section('content')
  <div class="container">
    <h1 class="title">{{ trans('torrent.torrent') }} {{ trans('torrent.peers') }}</h1>
    <div class="block">
    <div class="">
    <p class="lead">{{ trans('torrent.peers') }} For
    <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">{{ $torrent->name }}</a>
    </p>
    </div>
        <table class="table table-striped table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th>User</th>
              <th>Progress</th>
              <th>Uploaded</th>
              <th>Downloaded</th>
              <th>Left</th>
              <th>Client</th>
              <th>IP</th>
              <th>Port</th>
              <th>Started</th>
              <th>Last Update</th>
              <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($peers as $p)
          <tr>
            @if($p->user->peer_hidden == 1)
              <td><span class="badge-user text-orange text-bold"><i class="fa fa-eye-slash" aria-hidden="true"></i>ANONYMOUS</span> @if(Auth::user()->id == $p->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $p->username, 'id' => $p->id]) }}"><span class="badge-user text-bold" style="color:{{ $p->user->group->color }}">({{ $p->user->username }})</span></a>@endif</td>
            @else
              <td><a href="{{ route('profil', ['username' => $p->user->username, 'id' => $p->user->id]) }}"><span class="badge-user text-bold" style="color:{{ $p->user->group->color }}; background-image:{{ $p->user->group->effect }};"><i class="{{ $p->user->group->icon }}" data-toggle="tooltip" title="" data-original-title="{{ $p->user->group->name }}"></i> {{ $p->user->username }}</span></a></td>
            @endif
            @if ($p->seeder == 0)
            <td><div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $p->downloaded / $torrent->size * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $p->downloaded / $torrent->size * 100 }}%;">
            {{ round($p->downloaded / $torrent->size * 100) }}%
            </div></div></td>
            @elseif ($p->seeder == 1)
            <td><div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
            100%
            </div></div></td>
            @endif
            <td><span class="badge-extra text-green text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->uploaded, 2) }}</span></td>
            <td><span class="badge-extra text-red text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->downloaded, 2) }}</span></td>
            <td><span class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span></td>
            <td><span class="badge-extra text-purple text-bold">{{ $p->agent }}</span></td>
            @if(Auth::user()->group->is_modo)
            <td><span class="badge-extra text-bold">{{ $p->ip }}</span></td>
            <td><span class="badge-extra text-bold">{{ $p->port }}</span></td>
            @else
            <td> --- </td>
            <td> --- </td>
            @endif
            <td>{{ $p->created_at->diffForHumans() }}</td>
            <td>{{ $p->updated_at->diffForHumans() }}</td>
            <td> @if ($p->seeder == 0)
                    <span class='label label-danger'>LEECHER</span>
                @elseif ($p->seeder == 1)
                    <span class='label label-success'>SEEDER</span>
                @else
                    <span class='label label-warning'>ERROR</span>
                @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $peers->links() }}
    </div>
  </div>
@stop
