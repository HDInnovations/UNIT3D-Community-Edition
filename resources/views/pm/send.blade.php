@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('inbox') }}" class="breadcrumb__link">
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('content')
    <div class="container">
        <div>
            <div>
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
                            <textarea id="editor" name="message" cols="30" rows="10" class="form-control"></textarea>
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
