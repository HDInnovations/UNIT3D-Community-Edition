@extends('layout.default')

@section('breadcrumb')
<li class="active">
    <a href="{{ route('outbox', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.outbox') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('pm.private') }} {{ trans('pm.messages') }} - {{ trans('pm.outbox') }}</h1>
    </div>
  </div>
        <div class="row">
          <div class="col-md-2">
            <div class="block">
              <a href="{{ route('create', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" class="btn btn-primary btn-block">{{ trans('pm.new') }}</a>
              <div class="separator"></div>
              <div class="list-group">
                <a href="{{ route('inbox', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" class="btn btn-primary btn-block">{{ trans('pm.inbox') }}</a>
                <a href="{{ route('outbox', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}" class="btn btn-primary btn-block">{{ trans('pm.outbox') }}</a>
              </div>
            </div>
          </div>

        <div class="col-md-10">
            <div class="block">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <td class="col-sm-2">{{ trans('pm.to') }}</td>
                <td class="col-sm-6">{{ trans('pm.subject') }}</td>
                <td class="col-sm-2">{{ trans('pm.sent-at') }}</td>
              </tr>
            </thead>
            <tbody>
              @foreach($pms as $p)
              <tr>
                <td class="col-sm-2"><a href="{{ route('profile', ['username' => $p->receiver->username, 'id' => $p->receiver->id]) }}" title="">{{ $p->receiver->username}}</a></td>
                <td class="col-sm-5"><a href="{{ route('message', ['username' => $user->username , 'id' => $user->id , 'pmid' => $p->id]) }}">{{ $p->subject }}</a></td>
                <td class="col-sm-2">{{ $p->created_at->diffForHumans() }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $pms->links() }}
        </div>
      </div>
    </div>
    </div>
  @endsection
