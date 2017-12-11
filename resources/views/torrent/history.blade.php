@extends('layout.default')

@section('title')
<title>Peers - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ 'Download at maximum speed!' }}"> @stop

@section('breadcrumb')
<li>
<a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent</span>
</a>
</li>
<li class="active">
<a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
    <span itemprop="title" class="l-breadcrumb-item-link-title">History</span>
</a>
</li>
@stop

@section('content')
<div class="container">
<h1 class="title">Torrent History</h1>
<div class="block">
<div class="">
<p class="lead">History for
<a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">{{ $torrent->name }}</a>
</p>
</div>
<div class="table-responsive">
<table class="table table-condensed table-striped table-bordered">
<thead>
    <tr>
        <th>User</th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Active">Active<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Completed">Completed<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Upload">Upload<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Download">Download<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Added">Added<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Updated">Updated<i class="fa fa-fw fa-sort"></i></a></th>
        <th><a href="#" class="sort" data-toggle="tooltip" title="" data-original-title="Order by Updated">Seedtime<i class="fa fa-fw fa-sort"></i></a></th>
    </tr>
</thead>
<tbody>
    @foreach($history as $hpeers)
    <tr>
        @if($hpeers->user->peer_hidden == 1)
        <td><span class="badge-user text-orange text-bold"><i class="fa fa-eye-slash" aria-hidden="true"></i>ANONYMOUS</span> @if(Auth::user()->id == $hpeers->user->id || Auth::user()->group->is_modo)<a href="{{ route('profil', ['username' => $hpeers->user->username, 'id' => $hpeers->user->id]) }}"><span class="badge-user text-bold" style="color:{{ $hpeers->user->group->color }}">{{ $hpeers->user->username }}</span></a>@endif</td>
        @else
        <td><a href="{{ route('profil', ['username' => $hpeers->user->username, 'id' => $hpeers->user->id]) }}"><span class="badge-user text-bold" style="color:{{ $hpeers->user->group->color }}">{{ $hpeers->user->username }}</span></a></td>
        @endif
        @if($hpeers->active == 1) <td class="text-green">yes</td> @else <td class="text-red">no</td> @endif
        @if($hpeers->seeder == 1) <td class="text-green">yes</td> @else <td class="text-red">no</td> @endif
        <td>
            <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_uploaded,2) }}</span>
            <span class="badge-extra text-blue" data-toggle="tooltip" title="" data-original-title="Credited Upload">{{ App\Helpers\StringHelper::formatBytes($hpeers->uploaded,2) }}</span>
        </td>
        <td>
            <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($hpeers->actual_downloaded,2) }}</span>
            <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="Credited Download">{{ App\Helpers\StringHelper::formatBytes($hpeers->downloaded,2) }}</span>
        </td>
        <td>{{ $hpeers->created_at->diffForHumans() }}</td>
        <td>{{ $hpeers->updated_at->diffForHumans() }}</td>
        @if($hpeers->seedtime < 604800)
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
@stop
