@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li class="active">
    <a href="{{ route('message', array('username' => auth()->user()->username, 'id' => auth()->user()->id, 'pmid' => $pm->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.message') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('pm.private') }} {{ trans('pm.messages') }} - {{ trans('pm.message') }}</h1>
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
      <h3>Re: {{ $pm->subject }}</h3>
      @if ($pm->sender_id == auth()->user()->id)
      <div class="mt-10 message message-unread message-sent">
      @else
      <div class="mt-10 message message-read">
      @endif
        <div class="row message-headers">
          <div class="col-sm-4">
            <div><strong>{{ trans('pm.from') }}:</strong> <a href="{{ route('profile', ['username' => $pm->sender->username, 'id' => $pm->sender->id]) }}" title="">{{ $pm->sender->username }}</a>
            </div>
            <div><strong>{{ trans('pm.to') }}:</strong> <a href="{{ route('profile', ['username' => $pm->receiver->username, 'id' => $pm->receiver->id]) }}" title="">{{ $pm->receiver->username }}</a>
            </div>
          </div>
          <div class="col-sm-7">
            <div><strong>{{ trans('pm.subject') }}:</strong> Re: {{ $pm->subject }}</div>
            <div>
              <strong>{{ trans('pm.sent') }}:</strong> {{ $pm->created_at }}
            </div>
          </div>
          <form role="form" method="POST" action="{{ route('delete-pm',['pmid' => $pm->id]) }}">
          {{ csrf_field() }}
          <div class="col-sm-1">
            <button type="submit" class="btn btn-sm btn-danger pull-right" title="{{ trans('pm.delete') }}"><i class="fa fa-trash"></i></button>
          </div>
          </form>
        </div>
        <div class="row message-body">
          <div class="col-sm-12">
            @emojione($pm->getMessageHtml())
          </div>
        </div>
      </div>
      <form role="form" method="POST" action="{{ route('reply-pm',['pmid' => $pm->id]) }}">
      {{ csrf_field() }}
      <div class="form-group">
        <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
        <button type="submit" class="btn btn-primary" style="float:right;">{{ trans('pm.reply') }}</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
<script>
$(document).ready(function() {
    var wbbOpt = { }
    $("#message").wysibb(wbbOpt);
});
</script>
@endsection
