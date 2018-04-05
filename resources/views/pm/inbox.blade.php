@extends('layout.default')

@section('breadcrumb')
<li class="active">
    <a href="{{ route('inbox', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.inbox') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('pm.private') }} {{ trans('pm.messages') }} - {{ trans('pm.inbox') }}</h1>
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
                <div class="row">
                  <div class="col-md-8 col-xs-5">
                    <div class="btn-group">
                      <a href="{{ route('mark-all-read', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}">
                          <button type="button" id="mark-all-read" class="btn btn-success dropdown-toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('pm.mark-all-read') }}"><i class="fa fa-eye"></i></button>
                      </a>
                      <a href="{{ route('inbox', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}">
                          <button type="button" id="btn_refresh" class="btn btn-primary dropdown-toggle" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('pm.refresh') }}"><i class="fa fa-refresh"></i></button>
                      </a>
                      {{--<button type="button" id="btn_delete_messages" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('pm.delete') }}"><i class="fa fa-trash"></i></button>--}}
                      </div>
                      </div>
                      <div class="col-md-4 col-xs-7">
                    <div class="input-group">
                  <form role="form" method="POST" action="{{ route('searchPM',['username' => $user->username, 'id' => $user->id]) }}">
                  {{ csrf_field() }}
                  <input type="text" name="subject" id="subject" class="form-control" placeholder="{{ trans('pm.search') }}">
              </form>
            </div>
          </div>
        </div>
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                <td class="col-sm-1"><input type="checkbox" name="pm_id" value="0" class="selector all"></td>
                <td class="col-sm-2">{{ trans('pm.from') }}</td>
                <td class="col-sm-5">{{ trans('pm.subject') }}</td>
                <td class="col-sm-2">{{ trans('pm.recieved-at') }}</td>
                <td class="col-sm-2">{{ trans('pm.read') }}</td>
              </tr>
            </thead>
            <tbody>
              @foreach($pms as $p)
              {{ Form::hidden('invisible', 'id', array('id' => 'id')) }}
              <tr>
                <td class="col-sm-1"><input id="check" type="checkbox" name="pm_id" class="selector"></td>
                <td class="col-sm-2"><a href="{{ route('profile', ['username' => $p->sender->username, 'id' => $p->sender->id]) }}" title="">{{ $p->sender->username}}</a></td>
                <td class="col-sm-5"><a href="{{ route('message', ['username' => $user->username , 'id' => $user->id , 'pmid' => $p->id]) }}">{{ $p->subject }}</a></td>
                <td class="col-sm-2">{{ $p->created_at->diffForHumans() }}</td>
                @if ($p->read == 0)
                <td class="col-sm-2"><span class='label label-danger'>{{ trans('pm.unread') }}</span></td>
                @else ($p->read >= 1)
                <td class="col-sm-2"><span class='label label-success'>{{ trans('pm.read') }}</span></td>
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
@endsection
