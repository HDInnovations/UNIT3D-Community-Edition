@extends('layout.default')

@section('title')
    <title>My Active Table - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('myactive', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">My Active Table</span>
    </a>
</li>
@stop

@section('content')
<div class="container-fluid">
  <h1 class="title">My Active Table</h1>
    <div class="block">
      <!-- Active -->
      <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
        <div class="head"><strong>Active Torrents</strong></div>
        <thead>
          <th>@sortablelink('name')</th>
          <th>Category</th>
          <th>@sortablelink('size')</th>
          <th>@sortablelink('uploaded')</th>
          <th>@sortablelink('downloaded')</th>
          <th>@sortablelink('left')</th>
          <th>@sortablelink('agent')</th>
          <th>@sortablelink('seeder')</th>
        </thead>
        <tbody>
        @foreach ($active as $p)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $p->torrent_id }}" data-slug="{{ $p->torrent->slug }}" href="{{ route('torrent', array('slug' => $p->torrent->slug, 'id' => $p->torrent_id)) }}" data-toggle="tooltip" title="{{{ $p->torrent->name }}}" data-original-title="Moderated By {{ App\User::find($p->torrent->moderated_by)->username }} on {{ $p->torrent->moderated_at->diffForHumans() }}">{{{ $p->torrent->name }}}</a>
          </td>
          <td>
            <a href="{{ route('category', array('slug' => $p->torrent->category->slug, 'id' => $p->torrent->category->id)) }}">{{{ $p->torrent->category->name }}}</a>
          </td>
          <td>
            <span class="badge-extra text-blue text-bold"> {{ $p->torrent->getSize() }}</span>
          </td>
          <td>
            <span class="badge-extra text-green text-bold"> {{ App\Helpers\StringHelper::formatBytes($p->uploaded , 2) }}</span>
          </td>
          <td>
            <span class="badge-extra text-red text-bold"> {{ App\Helpers\StringHelper::formatBytes($p->downloaded , 2) }}</span>
          </td>
          <td>
            <span class="badge-extra text-orange text-bold">{{ \App\Helpers\StringHelper::formatBytes($p->left, 2) }}</span>
          </td>
          <td>
            <span class="badge-extra text-purple text-bold">{{ $p->agent ? $p->agent : "Unknown" }}</span>
          </td>
          @if ($p->seeder == 0)
          <td>
            <div class="progress">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{ $p->downloaded / $p->torrent->size * 100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $p->downloaded / $p->torrent->size * 100 }}%;">
                {{ round($p->downloaded / $p->torrent->size * 100) }}%
              </div>
            </div>
          </td>
          @elseif ($p->seeder == 1)
          <td>
            <div class="progress">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                100%
              </div>
            </div>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
      </table>
      {!! $active->appends(\Request::except('page'))->render() !!}
  </div>
</div>
@stop
