@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('create', ['receiver_id' => '', 'username' => '']) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ trans('pm.send') }} {{ trans('pm.message') }}
            </span>
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
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <form role="form" method="POST" action="{{ route('send-pm') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="users">{{ trans('pm.select') }}</label>
                            <select class="js-example-basic-single form-control" name="receiver_id">
                                    <option value="{{ $receiver_id }}">{{ $username }}</option>
                                @foreach($usernames as $username)
                                    <option value="{{ $username->id }}">{{ $username->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">{{ trans('pm.subject') }}</label>
                            <input name="subject" class="form-control" placeholder="{{ trans('pm.enter-subject') }}"
                                   required>
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
      $(document).ready(function () {
        $('.js-example-basic-single').select2()
      })
    </script>

    <script>
      $(document).ready(function () {
        $('#message').wysibb({})
        emoji.textcomplete()
      })
    </script>
@endsection
