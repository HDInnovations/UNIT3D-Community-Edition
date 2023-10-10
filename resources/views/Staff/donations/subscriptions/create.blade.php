@extends('layout.default')

@section('title')
    <title>{{ __('staff.vips') }} - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('staff.vips') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.donation_subscriptions.index') }}" class="breadcrumb__link">
            Donation Subscriptions
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.add') }}
    </li>
@endsection

@section('page', 'page__donations-subscriptions--create')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.add') }}
            {{ trans_choice('common.a-an-art',false) }}
            Subscription
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.donation_subscriptions.store') }}"
                enctype="multipart/form-data"
            >
                @csrf
                <p class="form__group">
                    <input
                        id="username"
                        class="form__text"
                        type="text"
                        name="username"
                        placeholder=" "
                    >
                    <label class="form__label form__label--floating" for="username">{{ __('common.username') }}<label>
                </p>
                <p class="form__group">
                    <input
                        id="donation_item_id"
                        class="form__text"
                        type="text"
                        name="donation_item_id"
                        placeholder=" "
                    >
                    <label class="form__label form__label--floating" for="donation_item_id">Item ID</label>
                </p>
                <p class="form__group">
                    <input
                        id="start_at"
                        class="form__text"
                        type="text"
                        name="start_at"
                        placeholder=" "
                    >
                    <label class="form__label form__label--floating" for="start_at">Start date (yyyy-mm-dd)</label>
                </p>
                <p class="form__group">
                    <input
                        id="end_at"
                        class="form__text"
                        type="text"
                        name="end_at"
                        placeholder=" "
                    >
                    <label class="form__label form__label--floating" for="end_at">End date (yyyy-mm-dd)</label>
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
