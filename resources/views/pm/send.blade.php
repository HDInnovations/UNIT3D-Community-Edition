@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('create', ['receiver_id' => '', 'username' => '']) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('pm.send') @lang('pm.message')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="header gradient silver">
            <div class="inner_content">
                <h1>@lang('pm.private') @lang('pm.message') - @lang('pm.create')</h1>
            </div>
        </div>
        <div class="row">
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <form role="form" method="POST" action="{{ route('send-pm') }}">
                        @csrf
                        <div class="form-group">
                            <label for="users">@lang('pm.select')</label>
                            <select class="js-example-basic-single form-control" name="receiver_id">
                                    <option value="{{ $receiver_id }}">{{ $username }}</option>
                                @foreach ($usernames as $username)
                                    <option value="{{ $username->id }}">{{ $username->username }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">@lang('pm.subject')</label>
                            <input name="subject" class="form-control" placeholder="@lang('pm.enter-subject')"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="">@lang('pm.message')</label>
                            <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                        </div>

                        <button class="btn btn-primary">
                            <i class="{{ config('other.font-awesome') }} fa-save"></i> @lang('pm.send')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('.js-example-basic-single').select2()
      })
    </script>

    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#message').wysibb({});
        emoji.textcomplete()
      })
    </script>
@endsection
