@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" class="breadcrumb__link">
            Donations
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.donations.subscriptions.index') }}" class="breadcrumb__link">
            Subscriptions
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__donations-subscriptions--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.edit') }} {{ $vip_sub->user->username }} VIP Subscription
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.donations.subscriptions.update', ['vip_sub' => $vip_sub->name, 'id' => $vip_sub->id]) }}"
                enctype="multipart/form-data"
            >
                @method('PATCH')
                @csrf
                <p class="form__group">
                    <input
                        id="username"
                        class="form__text"
                        type="text"
                        name="username"
                        value="{{ $vip_sub->user->username }}"
                        disabled
                    >
                    <label class="form__label form__label--floating" for="name">{{ __('common.username') }}<label>
                </p>
                <p class="form__group">
                    <input
                        id="start_at"
                        class="form__text"
                        type="text"
                        name="start_at"
                        value="{{ date('Y-m-d', strtotime($vip_sub->start_at)) }}"
                    >
                    <label class="form__label form__label--floating" for="start_at">Start date</label>
                </p>
                <p class="form__group">
                    <input
                        id="end_at"
                        class="form__text"
                        type="text"
                        name="end_at"
                        value="{{ date('Y-m-d', strtotime($vip_sub->end_at)) }}"
                    >
                    <label class="form__label form__label--floating" for="end_at">End date</label>
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
