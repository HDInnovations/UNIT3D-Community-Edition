@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('earnings.index', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('gifts.index', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ __('bon.gifts') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.send-gift') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--gift')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.gift-to') }}</h2>
        <div class="panel__body">
            <form id="send_bonus" class="form" method="POST" action="{{ route('gifts.store', ['username' => $user->username]) }}">
                @csrf
                <p class="form__group">
                    <input
                        id="users"
                        class="form__text"
                        name="to_username"
                        placeholder=""
                        required
                        type="text"
                    >
                    <label class="form__label form__label--floating" for="users">
                        {{ __('pm.select') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="bonus_points"
                        class="form__text"
                        inputmode="numeric"
                        name="bonus_points"
                        pattern="[0-9]*"
                        placeholder=""
                        required
                        type="text"
                    >
                    <label class="form__label form__label--floating" for="bonus_points">
                        {{ __('bon.amount') }}
                    </label>
                </p>
                <p class="form__group">
                    <textarea
                        id="bonus_message"
                        class="form__textarea"
                        name="bonus_message"
                        placeholder=""
                        required
                    ></textarea>
                    <label class="form__label form__label--floating" for="bonus_message">
                        {{ __('pm.message') }}
                    </label>
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

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $userbon }}</h2>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.no-refund') }}</h2>
        <div class="panel__body">{{ __('bon.exchange-warning') }}</div>
    </section>
@endsection