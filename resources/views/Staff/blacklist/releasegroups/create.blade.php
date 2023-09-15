@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" itemprop="url" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Blacklists</span>
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.blacklisted_releasegroups.index') }}" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Release Group</span>
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.add') }}
        </h2>
        <div class="panel__body">
            <form
                    name="upload"
                    class="upload-form form"
                    id="upload-form"
                    method="POST"
                    action="{{ route('staff.blacklisted_releasegroups.store') }}"
            >
                @csrf
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        name="name"
                        required
                        type="text"
                        value="{{ old('name') }}"
                    >
                    <label class="form__label form__label--floating" for="name">{{ __('common.name') }}</label>
                </p>
                <p class="form__group">
                    <input
                        id="reason"
                        class="form__text"
                        name="reason"
                        type="text"
                        value="{{ old('reason') }}"
                    >
                    <label class="form__label form__label--floating" for="reason">{{ __('common.reason') }}</label>
                </p>
                <p class="form__group">
                    <fieldset class="form__fieldset">
                        <legend class="form__legend">
                            None Ticked = Block all types <br>
                            Some Ticked = Block this RG from uploading the ticked types only
                        </legend>
                        <div class="form__fieldset-checkbox-container">
                            @foreach ($types as $type)
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            id="{{ $type->name }}"
                                            class="form__checkbox"
                                            name="types[]"
                                            type="checkbox"
                                            value="{{ $type->id }}"
                                        >
                                        {{ $type->name }}
                                    </label>
                                </p>
                            @endforeach
                        </div>
                    </fieldset>
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
