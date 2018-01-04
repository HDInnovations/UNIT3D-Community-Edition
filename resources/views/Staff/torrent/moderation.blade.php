@extends('layout.default')

@section('title')
<title>Moderation - Staff Dashboard - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
    </a>
</li>
<li class="active">
  <a href="{{ route('moderation') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Moderation</span>
  </a>
</li>
@stop

@section('content')
<center><h1>There are <span class="badge badge-danger">{{ $modder }}</span> torrents pending staff moderation!</h1></center>
<div class="container box">
  <div class="torrents col-md-12">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Since</th>
          <th>Name</th>
          <th>Category</th>
          <th>Type</th>
          <th>Size</th>
          <th>Uploader</th>
          <th>Approve</th>
          <th>Reject</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pending as $p)
        <tr>
        <td><span class="text-red text-bold">{{ $p->created_at->diffForHumans() }}</span></td>
        <td><a href="{{ route('torrent', ['slug' => $p->slug, 'id' => $p->id]) }}" itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title" class="l-breadcrumb-item-link-title">{{ $p->name }}</span></a></td>
        <td><i class="{{ $p->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $p->category->name }} Torrent"></i></td>
        <td>{{ $p->type }}</td>
        <td>{{ $p->getSize() }}</td>
        <td>{{ $p->user->username }}</td>
        <td><a href="{{ route('moderation_approve', ['slug' => $p->slug, 'id' => $p->id]) }}" role='button' class='btn btn-labeled btn-success'><span class="btn-label"><i class="fa fa-thumbs-up"></i></span>Approve</a></td>
        <td><a href="{{ route('moderation_reject', ['slug' => $p->slug, 'id' => $p->id]) }}" role='button' class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-thumbs-down"></i></span>Reject</a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<center><h1>Rejected Torrents</h1></center>
<div class="container box">
  <div class="torrents col-md-12">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
              <th>Since</th>
              <th>Name</th>
              <th>Category</th>
              <th>Type</th>
              <th>Size</th>
              <th>Uploader</th>
              <th>Approve</th>
              @if(Auth::check() && Auth::user()->group->is_admin)
              <th>Edit</th>
              <th>Delete</th>
              @endif
            </tr>
        </thead>
        <tbody>
            @foreach($rejected as $reject)
            <tr>
                <td><span class="text-red text-red">{{ $reject->created_at->diffForHumans() }}</span></td>
                <td><a href="{{ route('torrent', ['slug' => $reject->slug, 'id' => $reject->id]) }}" itemprop="url" class="l-breadcrumb-item-link"><span itemprop="title" class="l-breadcrumb-item-link-title">{{ $reject->name }}</span></a></td>
                <td><i class="{{ $reject->category->icon }} torrent-icon" data-toggle="tooltip" title="" data-original-title="{{ $reject->category->name }} Torrent"></i></td>
                <td>{{ $reject->type }}</td>
                <td>{{ $reject->getSize() }}</td>
                <td>{{ $reject->user ? $reject->user->username : "System" }}</td>
                <td><a href="{{ route('moderation_approve', ['slug' => $reject->slug, 'id' => $reject->id]) }}" role='button' class='btn btn-labeled btn-success'><span class="btn-label"><i class="fa fa-thumbs-up"></i></span>Approve</a></td>
                <td><a href="{{ route('edit', array('slug' => $reject->slug, 'id' => $reject->id)) }}" role='button' class='btn btn-labeled btn-info'><span class="btn-label"><i class="fa fa-pencil"></i></span>Edit</a></td>
                <td><a href="{{ action('TorrentController@deleteTorrent', array('id' => $reject->id)) }}" role='button' class='btn btn-labeled btn-danger'><span class="btn-label"><i class="fa fa-thumbs-down"></i></span>Delete</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
@stop
