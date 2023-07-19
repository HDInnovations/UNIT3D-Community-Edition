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
        {{ __('user.two-step-auth.title') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    @if (session('status') == 'two-factor-authentication-confirmed')
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.two-step-auth.totp') }}</h2>
            <div class="panel__body">
                <p>{{ __('user.two-step-auth.totp-is-enabled') }}</p>
                <form
                    class="form"
                    action="{{ route('two-factor.disable') }}"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')
                    <p>{{ __('user.two-step-auth.password-confirm') }}</p>
                    <p class="form__group">
                        <button class="form__button form__button--filled">
                            {{ __('common.disable') }}
                        </button>
                    </p>
                </form>
            </div>
        </section>
    @elseif (session('status') == 'two-factor-authentication-enabled')
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.two-step-auth.totp') }}</h2>
            <div class="panel__body">
                <p>{{ __('user.two-step-auth.complete-setup') }}</p>
                <form
                        class="form"
                        action="{{ route('two-factor.confirm') }}"
                        method="POST"
                >
                    @csrf
                    <div class="twoStep__qrCode">
                        <img src="data:image/svg+xml;base64,{{base64_encode(request()->user()->twoFactorQrCodeSvg())}}" alt="2FA QR Code">
                    </div>
                    <div class="form__group">
                        <input type="text" class="form__text" name="code" id="code" value="" required>
                        <label class="form__label form__label--floating" for="code">{{ __('user.two-step-auth.confirm-code') }}</label>
                    </div>
                    <div class="form__group">
                        <button class="form__button form__button--filled">
                            {{ __('common.enable') }}
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @else
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.two-step-auth.totp') }}</h2>
            <div class="panel__body">
                <p>{{ __('user.two-step-auth.totp-is-disabled') }}</p>
                <form
                        class="form"
                        action="{{ route('two-factor.enable') }}"
                        method="POST"
                >
                    @csrf
                    <p>{{ __('user.two-step-auth.password-confirm') }}</p>
                    <p>{{ __('user.two-step-auth.upon-enabling') }}</p>
                    <p class="form__group">
                        <button class="form__button form__button--filled">
                            {{ __('common.enable') }}
                        </button>
                    </p>
                </form>
            </div>
        </section>

        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.two-step-auth.email') }}</h2>
            <div class="panel__body">
                <form
                        class="form"
                        action="{{ route('users.two_step.update', ['user' => $user]) }}"
                        method="POST"
                >
                    @csrf
                    @method('PATCH')
                    <p>Upon enabling, you will receive an email including a code to your registered email address.</p>
                    <p>Token-based two factor authentication is planned for a future update.</p>
                    <div class="form__group">
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
                    </div>
                    <div class="form__group">
                        <button class="form__button form__button--filled">
                            {{ __('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @endif
@endsection


