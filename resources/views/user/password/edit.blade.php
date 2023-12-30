@extends('layout.default')

@section('title')
    <title>
        {{ $user->username }} - Security - {{ __('common.members') }} -
        {{ config('other.title') }}
    </title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a
            href="{{ route('users.general_settings.edit', ['user' => $user]) }}"
            class="breadcrumb__link"
        >
            {{ __('user.settings') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.password') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.change-password') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                action="{{ route('users.password.update', ['user' => $user]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')
                <p>{{ __('user.change-password-help') }}.</p>
                <p>
                    We strongly recommend you use a password manager (such as the free version of
                    Bitwarden) to generate a secure random password
                </p>
                @if (auth()->id() == $user->id)
                    <p class="form__group">
                        <input
                            id="current_password"
                            class="form__text"
                            autocomplete="current-password"
                            name="current_password"
                            placeholder=" "
                            required
                            type="password"
                        />
                        <label class="form__label form__label--floating" for="current_password">
                            Current Password
                        </label>
                    </p>
                @endif

                <livewire:password-strength />
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
