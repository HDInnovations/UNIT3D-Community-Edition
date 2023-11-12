
@extends('layout.default')

@section('title')
    <title>{{ __('auth.password-confirm.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('auth.password-confirm.title') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/main/twostep.css') }}" crossorigin="anonymous">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('auth.password-confirm.breadcrumb') }}
    </li>
@endsection

@section('content')
<section class="panelV2">
    <h2 class="panel__heading">{{ __('auth.password-confirm.title') }}</h2>
    <div class="panel__body">
        <form
            class="form"
            action="{{ route('password.confirm') }}"
            method="POST"
        >
            @csrf
            <p>{{ __('auth.password-confirm.description') }}</p>
            <p class="form__group">
                <input
                    type="password"
                    class="form__text"
                    id="password"
                    name="password"
                    required
                >
                <label class="form__label form__label--floating" for="password">{{ __('auth.password-confirm.input') }}</label>
                @error('error')
                    <span class="form__hint">{{ $error }}</span>
                @enderror
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('auth.password-confirm.button') }}
                </button>
            </p>
        </form>
    </div>
</section>
@endsection