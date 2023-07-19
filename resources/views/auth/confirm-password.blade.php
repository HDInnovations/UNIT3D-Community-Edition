
@extends('layout.default')

@section('title')
    <title>{{ __('auth.exceededTitle') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('auth.exceededTitle') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/main/twostep.css') }}" crossorigin="anonymous">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('auth.exceededTitle') }}
    </li>
@endsection

@section('content')
<section class="panelV2">
    <h2 class="panel__heading">Password Confirmation</h2>
    <div class="panel__body">
        <form
            class="form"
            action="{{ route('password.confirm') }}"
            method="POST"
        >
            @csrf
            <p>Please confirm your password before continuing.</p>
            <p class="form__group">
                <input
                    type="password"
                    class="form__text"
                    id="password"
                    name="password"
                >
                <label class="form__label" for="password">Password</label>
                @error('error')
                    <span class="form__hint">{{ $error }}</span>
                @enderror
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.confirm') }}
                </button>
            </p>
        </form>
</section>
@endsection