@extends('layout.default')

@section('title')
    <title>{{ __('auth.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('auth.title') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/main/twostep.css') }}" crossorigin="anonymous">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('auth.title') }}
    </li>
@endsection

@php
    switch ($remainingAttempts) {
        case 0:
        case 1:
            $remainingAttemptsClass = 'danger';
            break;

        case 2:
            $remainingAttemptsClass = 'warning';
            break;

        case 3:
            $remainingAttemptsClass = 'info';
            break;

        default:
            $remainingAttemptsClass = 'success';
            break;
    }
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel verification-form-panel">
                    <div class="panel__heading text-center" id="verification_status_title">
                        <h3>
                            {{ __('auth.title') }}
                        </h3>
                        <p class="text-center">
                            <em>
                                {{ __('auth.subtitle') }}
                            </em>
                        </p>
                    </div>
                    <div class="panel__body">
                        <form
                            class="form"
                            action="{{ route('two-factor.login') }}"
                            method="POST"
                        >
                            @csrf
                            <p class="form__group">
                                <input
                                    id="code"
                                    class="form__text"
                                    autofocus
                                    name="code"
                                    type="text"
                                />
                                <label class="form__label form__label--floating">
                                    {{ __('auth.code') }}
                                </label>
                                @error('error')
                                    <span class="form__hint">{{ $error }}</span>
                                @enderror
                            </p>
                            <p class="form__group">
                                <input
                                    id="code"
                                    class="form__text"
                                    autofocus
                                    name="receover_code"
                                    type="text"
                                />
                                <label class="form__label form__label--floating">
                                    {{ __('auth.recovery-code') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.submit') }}
                                </button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
