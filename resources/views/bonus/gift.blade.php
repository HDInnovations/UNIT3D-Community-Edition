@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('bonus') }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.send-gift') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="some-padding">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="well">
                            <div class="container-fluid">
                                <form role="form" method="POST" action="{{ route('bonus_send_gift') }}" id="send_bonus">
                                    @csrf

                                    <div class="form-group">
                                        <label for="to_username">{{ __('bon.gift-to') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="users">{{ __('pm.select') }}</label>
                                        <label>
                                            <input name="to_username" class="form-control"
                                                   placeholder="{{ __('common.username') }}" required>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="bonus_points">{{ __('bon.amount') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control"
                                               placeholder="{{ __('common.enter') }} {{ strtolower(__('common.amount')) }}"
                                               name="bonus_points" type="number" id="bonus_points" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="bonus_message">{{ __('pm.message') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="bonus_message" cols="50" rows="10"
                                                  id="bonus_message"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" value="{{ __('common.submit') }}">
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">

                        <div class="text-blue well well-sm text-center">
                            <h2><strong>{{ __('bon.your-points') }}: <br></strong>{{ $userbon }}</h2>
                        </div>

                        <div class="well well-sm mt-20">
                            <p class="lead text-orange text-center">{{ __('bon.exchange-warning') }}
                                <br><strong>{{ __('bon.no-refund') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
