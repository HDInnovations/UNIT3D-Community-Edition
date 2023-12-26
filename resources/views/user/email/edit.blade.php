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
        {{ __('common.email') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.change-email') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                action="{{ route('users.email.update', ['user' => $user]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')
                <p>{{ __('user.change-email-help') }}.</p>
                <p class="form__group">
                    <input
                        id="current_email"
                        class="form__text"
                        name="current_email"
                        readonly
                        type="email"
                        value="{{ $user->email }}"
                    />
                    <label class="form__label form__label--floating" for="current_email">
                        Current Email
                    </label>
                </p>
                <p class="form__group">
                    <input id="email" class="form__text" name="email" required type="email" />
                    <label class="form__label form__label--floating" for="email">New Email</label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.submit') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
