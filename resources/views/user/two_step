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
        {{ __('user.passkey') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Two Step Authentication</h2>
        <div class="panel__body">
            <form
                class="form"
                action="{{ route('users.two_step.update', ['user' => $user]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')
                <p>Currently, only email-based two step authentication is supported. Upon enabling, you will receive an email including a code to your registered email address.</p>
                <p>Token-based two factor authentication is planned for a future update.</p>
                <p class="form__group">
                    <input type="hidden" name="twostep" value="0">
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="twostep"
                        name="twostep"
                        value="1"
                        @checked($user->twostep)
                    >
                    <label class="form__label" for="internal">Enable two-step authentication</label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
