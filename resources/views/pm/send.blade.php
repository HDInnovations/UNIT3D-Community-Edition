@extends('layout.default')

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('create', ['receiver_id' => '', 'username' => '']) }}">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                {{ __('pm.send') }} {{ __('pm.message') }}
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.pmmenu')
            <div class="col-md-10">
                <div class="block">
                    <form role="form" method="POST" action="{{ route('send-pm') }}">
                        @csrf
                        <div class="form-group">
                            <label for="users">{{ __('common.username') }}</label>
                            <label>
                                <label>
                                    <input name="receiver_id" class="form-control"
                                           placeholder="{{ __('common.username') }}"
                                           {{request()->has('username') ? 'readonly' : 'required'}} value="{{request()->has('username') ? request()->get('username') : '' }}">
                                </label>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('pm.subject') }}</label>
                            <label>
                                <input name="subject" class="form-control" placeholder="{{ __('pm.enter-subject') }}"
                                       required>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('pm.message') }}</label>
                            <label for="message"></label>
                            <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                        </div>

                        <button class="btn btn-primary">
                            <i class="{{ config('other.font-awesome') }} fa-save"></i> {{ __('pm.send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('#message').wysibb({})
      })

    </script>
@endsection
