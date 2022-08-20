@extends('layout.default')

@section('title')
    <title>Gifting - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gifting - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.user-gifting') }}
    </li>
@endsection

@section('page', 'page__gift--index')

@section('content')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.gifts') }}</h2>
        <div class="panel__body">
            <form class="form" action="{{ route('staff.gifts.store') }}" method="POST">
                @csrf
                <p class="form__group">
                    <input
                        id="users"
                        class="form__text"
                        name="username"
                        required
                        type="text"
                    >
                    <label class="form__label form__label--floating" for="users">
                        {{ __('common.username') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="seedbonus"
                        class="form__text"
                        inputmode="numeric"
                        name="seedbonus"
                        pattern="[0-9]*"
                        required
                        type="text"
                        value="0"
                    >
                    <label class="form__label form__label--floating" for="seedbonus">
                        {{ __('bon.bon') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="invites"
                        class="form__text"
                        inputmode="numeric"
                        name="invites"
                        pattern="[0-9]*"
                        required
                        type="text"
                        value="0"
                    >
                    <label class="form__label form__label--floating" for="invites">
                        {{ __('user.invites') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        inputmode="numeric"
                        name="fl_tokens"
                        pattern="[0-9]*"
                        required
                        type="text"
                        value="0"
                    >
                    <label class="form__label form__label--floating" for="fl_tokens">
                        {{ __('torrent.freeleech-token') }}
                    </label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('bon.send-gift') }}
                    </button>
                </p>
            </form>
        </div>
    </div>
@endsection
