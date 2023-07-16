@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - Security - {{ __('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('users.general_settings.edit', ['user' => $user]) }}" class="breadcrumb__link">
            {{ __('user.settings') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Passkey
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.reset-passkey') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                action="{{ route('users.passkey.update', ['user' => $user]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')
                <p>{{ __('user.reset-passkey-help') }}.</p>
                <p class="form__group">
                    <input
                        id="current_passkey"
                        class="form__text"
                        name="current_passkey"
                        readonly
                        type="text"
                        value="{{ $user->passkey }}"
                    >
                    <label class="form__label form__label--floating" for="current_passkey">Current Passkey</label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        Reset
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
