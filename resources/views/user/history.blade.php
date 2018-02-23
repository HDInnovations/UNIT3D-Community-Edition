@extends('layout.default')

@section('title')
    <title>My History Table - {{ Config::get('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('myhistory', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">My History Table</span>
    </a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  <h1 class="title">My History Table</h1>
    <div class="block">
      <!-- History -->
      <span class="badge-user" style="float: right;"><strong>Total Download:</strong>
          <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his_downl,2) }}</span>
          <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="Credited Download">{{ App\Helpers\StringHelper::formatBytes($his_downl_cre,2) }}</span>
     </span>
      <span class="badge-user" style="float: right;"><strong>Total Upload:</strong>
        <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his_upl,2) }}</span>
        <span class="badge-extra text-blue" data-toggle="tooltip" title="" data-original-title="Credited Upload">{{ App\Helpers\StringHelper::formatBytes($his_upl_cre,2) }}</span>
      </span>
      <br>
      <br>
      <div class="table-responsive">
        <table class="table table-condensed table-striped table-bordered">
        <div class="head"><strong>Torrents History</strong></div>
        <thead>
          <th>@sortablelink('name')</th>
          <th>@sortablelink('agent')</th>
          <th>@sortablelink('active')</th>
          <th>@sortablelink('seeder')</th>
          <th>@sortablelink('uploaded')</th>
          <th>@sortablelink('downloaded')</th>
          <th>@sortablelink('seedtime')</th>
          <th>@sortablelink('created_at')</th>
          <th>@sortablelink('updated_at')</th>
          <th>@sortablelink('completed_at')</th>
      </thead>
      <tbody>
          @foreach($history as $his)
        <tr>
          <td>
            <a class="view-torrent" data-id="{{ $his->torrent_id }}" data-slug="{{ $his->torrent->slug }}" href="{{ route('torrent', array('slug' => $his->torrent->slug, 'id' => $his->torrent->id)) }}" data-toggle="tooltip" title="{{ $his->torrent->name }}" data-original-title="Moderated By {{ App\User::find($his->torrent->moderated_by)->username }} on {{ $his->torrent->moderated_at->diffForHumans() }}">{{ $his->torrent->name }}</a>
          </td>
          <td><span class="badge-extra text-purple">{{ $his->agent ? $his->agent : "Unknown" }}</span></td>
          @if($his->active == 1) <td class="text-green">yes</td> @else <td class="text-red">no</td> @endif
          @if($his->seeder == 1) <td class="text-green">yes</td> @else <td class="text-red">no</td> @endif
          <td>
            <span class="badge-extra text-green">{{ App\Helpers\StringHelper::formatBytes($his->actual_uploaded , 2) }}</span>
            <span class="badge-extra text-blue" data-toggle="tooltip" title="" data-original-title="Credited Upload">{{ App\Helpers\StringHelper::formatBytes($his->uploaded , 2) }}</span>
          </td>
          <td>
            <span class="badge-extra text-red">{{ App\Helpers\StringHelper::formatBytes($his->actual_downloaded , 2) }}</span>
            <span class="badge-extra text-orange" data-toggle="tooltip" title="" data-original-title="Credited Download">{{ App\Helpers\StringHelper::formatBytes($his->downloaded , 2) }}</span>
          </td>
          @if($his->seedtime < 604800)
            <td><span class="badge-extra text-red">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span></td>
          @else
            <td><span class="badge-extra text-green">{{ App\Helpers\StringHelper::timeElapsed($his->seedtime) }}</span></td>
          @endif
          <td>{{ $his->created_at->diffForHumans() }}</td>
          <td>{{ $his->updated_at->diffForHumans() }}</td>
          <td>{{ $his->completed_at ? $his->completed_at->diffForHumans() : "N/A"}}</td>
        </tr>
        @endforeach
      </tbody>
      </table>
      {!! $history->appends(\Request::except('page'))->render() !!}
  </div>
</div>
@endsection
