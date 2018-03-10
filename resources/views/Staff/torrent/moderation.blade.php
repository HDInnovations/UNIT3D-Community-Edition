@extends('layout.default')

@section('title')
<title>Moderation - Staff Dashboard - {{ Config::get('other.title') }}</title>
@endsection

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
@endsection

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
          <th>Postpone</th>
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
        <td><a data-target="#postpone-{{ $p->id }}" role="button" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-thumbs-down"></i></span>Postpone</a></td>
        <td><a data-target="#reject-{{ $p->id }}" role='button' class="btn btn-labeled btn-danger"><span class="btn-label"><i class="fa fa-thumbs-down"></i></span>Reject</a></td>
        </tr>
        <!-- Torrent Postpone Modal-->
        {{-- Torrent Postpone Modal --}}
        <div class="modal fade" id="postpone-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <meta charset="utf-8">
                    <title>Postpone Torrent: {{ $p->name }}</title>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Postpone Torrent: {{ $p->name }}</h4>
                    </div>
                    <div class="modal-body">
                        {{ Form::open(['route' => ['moderation_postpone'], 'method' => 'post']) }}
                        <div class="form-group">
                            <input id="type" name="type" type="hidden" value="Torrent">
                            <input id="id" name="id" type="hidden" value="{{ $p->id }}">
                            <input id="slug" name="slug" type="hidden" value="{{ $p-slug }}">
                            <label for="postpone_reason" class="col-sm-2 control-label">Reason (Sent To Uploader Via PM)</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                              <input class="btn btn-danger" type="submit" value="Postpone">
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Torrent Postpone Modal -->

        <!-- Torrent Reject Modal -->
        {{-- Torrent Reject Modal --}}
        <div class="modal fade" id="reject-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <meta charset="utf-8">
              <title>Reject Torrent: {{ $p->name }}</title>
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Reject Torrent: {{ $p->name }}</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                {{ Form::open(['route' => ['moderation_reject'] , 'method' => 'post']) }}
                  <input id="type" type="hidden" name="type" value="Torrent">
                  <input id="id" type="hidden" name="id" value="{{ $p->id }}">
                  <input id="slug" type="hidden" name="slug" value="{{ $p->slug }}">
                  <label for="file_name" class="col-sm-2 control-label">Torrent</label>
                  <div class="col-sm-10">
                    <label id="title" name="title" type="hidden" value="{{ $p->name }}">
                    <p class="form-control-static">{{ $p->name }}</p>
                  </div>
                </div>
                <div class="form-group">
                  <label for="report_reason" class="col-sm-2 control-label">Reason (Sent To Uploader Via PM)</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10 col-sm-offset-2">
                    <input class="btn btn-danger" type="submit" value="Reject">
                  </div>
                {{ Form::close() }}
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End Torrent Reject Modal -->
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<center><h1>Postponed Torrents</h1></center>
<div class="container box">
<div class="torrents col-med-12">
<table class="table table-bordered table-hover">
<thead>
    <th>Since</th>
    <th>Name</th>
    <th>Category</th>
    <th>Type</th>
    <th>Size</th>
    <th>Uploader</th>
    <th>Approve</th>
    <th>Edit</th>
    <th>Reject</th>
    <th>Delete</th>
</thead>
<tbody>

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
              <th>Edit</th>
              <th>Delete</th>
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
@endsection
