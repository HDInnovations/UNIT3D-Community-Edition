@extends('layout.default')

@section('breadcrumb')
<li class="active">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Inbox</span>
</li>
@stop

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>Private Messages - Inbox</h1>
    </div>
  </div>
        <div class="row">
          <div class="col-md-2">
            <div class="block">
              <a href="{{ route('create', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="btn btn-primary btn-block">New Message</a>
              <div class="separator"></div>
              <div class="list-group">
                <a href="{{ route('inbox', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="btn btn-primary btn-block">Inbox</a>
                <a href="{{ route('outbox', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}" class="btn btn-primary btn-block">Outbox</a>
              </div>
            </div>
          </div>

          <div class="col-md-10">
            <div class="block">
                <div class="row">
                  <div class="col-md-8 col-xs-5">
                    <div class="btn-group">
                      <a href="{{ route('inbox', array('username' => Auth::user()->username, 'id' => Auth::user()->id)) }}"><button type="button" id="btn_refresh" class="btn btn-primary dropdown-toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh">
                      <i class="fa fa-refresh"></i></button></a>
                      <button type="button" id="btn_delete_messages" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash"></i></button>
                      </div>
                      </div>
                      <div class="col-md-4 col-xs-7">
                    <div class="input-group">
                  {{ Form::open(array('route' => array('searchPM', 'username' => $user->username, 'id' => $user->id))) }}
                  <input type="text" name="subject" id="subject" class="form-control" placeholder="Search By Subject">
                {{ Form::close() }}
            </div>
          </div>
        </div>
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <td class="col-sm-1"><input type="checkbox" name="pm_id" value="0" class="selector all"></td>
                <td class="col-sm-2">From</td>
                <td class="col-sm-5">Subject</td>
                <td class="col-sm-2">Recieved At</td>
                <td class="col-sm-2">Read</td>
              </tr>
            </thead>
            <tbody>
              @foreach($pms as $p)
              {{ Form::hidden('invisible', 'id', array('id' => 'id')) }}
              <tr>
                <td class="col-sm-1"><input id="check" type="checkbox" name="pm_id" class="selector"></td>
                <td class="col-sm-2"><a href="{{ route('profil', ['username' => $p->sender->username, 'id' => $p->sender->id]) }}" title="">{{ $p->sender->username}}</a></td>
                <td class="col-sm-5"><a href="{{ route('message', ['username' => $user->username , 'id' => $user->id , 'pmid' => $p->id]) }}">{{ $p->subject }}</a></td>
                <td class="col-sm-2">{{ $p->created_at->diffForHumans() }}</td>
                @if ($p->read == 0)
                <td class="col-sm-2"><span class='label label-danger'>UNREAD</span></td>
                @else ($p->read >= 1)
                <td class="col-sm-2"><span class='label label-success'>READ</span></td>
                @endif
              </tr>
              {{ Form::close() }}
              @endforeach
            </tbody>
          </table>
          {{ $pms->links() }}
        </div>
      </div>
    </div>
  </div>
@stop
