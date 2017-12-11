@extends('layout.default')

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@stop

@section('breadcrumb')
<li class="active">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Send Message</span>
</li>
@stop

@section('content')
<div class="container">
  <div class="header gradient silver">
    <div class="inner_content">
      <h1>Private Message - Create</h1>
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
                  {{ Form::open(array('route' => array('send-pm'))) }}
                  <div class="form-group">
                    <label for="users">Select a User</label>
                    <select class="form-control user-select-placeholder-single" name="reciever_id">
                      @foreach($usernames as $username)
                        <option value="{{ $username->id }}">{{ $username->username }}</option>
                      @endforeach
                    </select>
                    </div>

                  <div class="form-group">
                    <label for="">Subject</label>
                    <input name="subject" class="form-control" placeholder="Enter subject">
                  </div>

                  <div class="form-group">
                    <label for="">Message</label>
                    <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                  </div>

                  <button class="btn btn-primary">
                    <i class="fa fa-save"></i> Send
                  </button>
                {{ Form::close() }}
      </div>
    </div>
  </div>
</div>
@stop

@section('javascripts')
    <script type="text/javascript">
        $('.user-select-placeholder-single').select2({
            placeholder: "Select A User",
            allowClear: true
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
