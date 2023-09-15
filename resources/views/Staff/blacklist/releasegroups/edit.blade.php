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
        {{ __('common.edit') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('common.edit') }}
        </h2>
        <div class="panel__body">
            <form
                    name="upload"
                    class="upload-form form"
                    id="upload-form"
                    method="POST"
                    action="{{ route('staff.blacklisted_releasegroups.update', ['blacklistReleasegroup' => $releasegroup->id]) }}"
            >
                @csrf
                @method('patch')
                <p class="form__group">
                    <input
                            type="text"
                            name="name"
                            id="name"
                            class="form__text"
                            value="{{ $releasegroup->name }}"
                            required
                    >
                    <label class="form__label form__label--floating" for="name">{{ __('common.name') }}</label>
                </p>
                <p class="form__group">
                    <input
                            type="text"
                            name="reason"
                            id="reason"
                            class="form__text"
                            value="{{ $releasegroup->reason }}"
                    >
                    <label class="form__label form__label--floating" for="reason">
                        {{ __('common.reason') }}
                    </label>
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
                                    <input
                                            id="{{ $type->name }}"
                                            class="form__checkbox"
                                            name="types[]"
                                            type="checkbox"
                                            value="{{ $type->id }}"
                                            @checked(is_array($releasegroup->object_releasegroup->types ?? '') && in_array((string)$type->id, $releasegroup->object_releasegroup->types ?? '', true))
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
