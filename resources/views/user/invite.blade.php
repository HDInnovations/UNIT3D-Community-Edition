@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - {{ __('user.send-invite') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('invites.index', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}
                {{ __('user.send-invite') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @if (config('other.invite-only') == false)
            <div class="container">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i>
                            {{ __('user.invites-disabled') }}
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">{{ __('user.invites-disabled-desc') }}</p>
                    </div>
                </div>
            </div>
        @elseif ($user->can_invite == 0)
            <div class="container">
                <div class="jumbotron shadowed">
                    <div class="container">
                        <h1 class="mt-5 text-center">
                            <i class="{{ config('other.font-awesome') }} fa-times text-danger"></i> {{ __('user.invites-banned') }}
                        </h1>
                        <div class="separator"></div>
                        <p class="text-center">{{ __('user.invites-banned-desc') }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="block">
                @include('user.buttons.invite')
                <div class="some-padding">
                    <div class="container-fluid">
                        <div class="block">
                            <h2>{{ __('user.invites-count', ['count' => $user->invites]) }}</h2>
                            <p class="text-danger text-bold">{{ __('user.important') }}</p>
                            <ul>
                                {!! __('user.invites-rules') !!}
                            </ul>
                        </div>

                        <h3>{{ __('user.invite-friend') }}</h3>
                        <div class="block">
                            <form action="{{ route('invites.store') }}" method="POST">
                                @csrf
                                <div class="form-group"><label for="email">{{ __('common.email') }}</label></div>
                                <div class="form-group"><input class="form-control" name="email" type="email" id="email"
                                                               size="10" required></div>
                                <div class="form-group"><label for="message">{{ __('common.message') }}</label></div>
                                <div class="form-group">
                                    <textarea class="form-control" name="message" cols="50" rows="10"
                                              id="message"></textarea></div>
                                <div class="form-group">
                                    <button type="submit"
                                            class="btn btn-primary">{{ __('common.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @endif
@endsection
