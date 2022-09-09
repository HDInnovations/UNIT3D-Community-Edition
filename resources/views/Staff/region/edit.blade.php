@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.regions.index') }}" class="breadcrumb__link">
            Torrent Regions
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $region->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__region--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.edit') }} Torrent Region: {{ $region->name }}
        </h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.regions.update', ['id' => $region->id]) }}"
            >
                @method('PATCH')
                @csrf
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        name="name"
                        required
                        type="text"
                        value="{{ $region->name }}"
                    >
                    <label class="form__label form__label--floating" for="name">
                        {{ __('common.name') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        inputmode="numeric"
                        name="position"
                        pattern="[0-9]*"
                        required
                        type="text"
                        value="{{ $region->position }}"
                    >
                    <label class="form__label form__label--floating" for="name">
                        {{ __('common.position') }}
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
@endsection
