@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li class="active">
    <a href="{{ route('create', array('username' => auth()->user()->username, 'id' => auth()->user()->id)) }}">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('pm.send') }} {{ trans('pm.message') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>{{ trans('pm.private') }} {{ trans('pm.message') }} - {{ trans('pm.create') }}</h1>
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
                  <form role="form" method="POST" action="{{ route('send-pm') }}">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="users">{{ trans('pm.select') }}</label>
                    <select class="js-example-basic-single form-control" name="reciever_id">
                      @foreach($usernames as $username)
                        <option value="{{ $username->id }}">{{ $username->username }}</option>
                      @endforeach
                    </select>
                    </div>

                  <div class="form-group">
                    <label for="">{{ trans('pm.subject') }}</label>
                    <input name="subject" class="form-control" placeholder="{{ trans('pm.enter-subject') }}" required>
                  </div>

                  <div class="form-group">
                    <label for="">{{ trans('pm.message') }}</label>
                    <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                  </div>

                  <button class="btn btn-primary">
                    <i class="fa fa-save"></i> {{ trans('pm.send') }}
                  </button>
              </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascripts')
    <script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    </script>

    <script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>
    <script>
    $(document).ready(function() {
        var wbbOpt = { }
        $("#message").wysibb(wbbOpt);
    });
    </script>
@endsection
