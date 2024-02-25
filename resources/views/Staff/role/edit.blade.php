@extends('layout.default')

@php
    use App\Enums\Permission;
@endphp

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.roles.index') }}" class="breadcrumb__link">
            {{ __('rbac.roles') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $role->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__category--index')

@section('main')
    <section class="panelV2" x-data="{ auto_manage: @js($role->auto_manage) }">
        <h2 class="panel__heading">
            {{ __('common.edit') }}
        </h2>
        <div class="panel__body">
            <form
                id="role_edit_form"
                class="form"
                method="POST"
                action="{{ route('staff.roles.update', ['role' => $role]) }}"
            >
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        name="role[name]"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $role->name }}"
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('common.name') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        inputmode="numeric"
                        name="role[position]"
                        pattern="[0-9]*"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $role->position }}"
                    />
                    <label class="form__label form__label--floating" for="position">
                        {{ __('common.position') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="description"
                        class="form__text"
                        name="role[description]"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $role->description }}"
                    />
                    <label class="form__label form__label--floating" for="description">
                        {{ __('common.description') }}
                    </label>
                </p>
                <p class="form__group">
                    <input name="role[auto_manage]" type="hidden" value="0" />
                    <input
                        id="auto_manage"
                        class="form__checkbox"
                        name="role[auto_manage]"
                        type="checkbox"
                        value="1"
                        x-model="auto_manage"
                        @checked($role->auto_manage)
                    />
                    <label class="form__label" for="auto_manage">
                        Automatically add and remove this role from users
                    </label>
                </p>
                <div class="form__group" x-show="auto_manage">
                    <fieldset class="form form__fieldset">
                        <legend class="form__legend">Conditions</legend>
                        <div class="form__group--horizontal">
                            <p class="form__group">
                                <input
                                    id="warnings_balance_min"
                                    class="form__text"
                                    type="text"
                                    name="role[warnings_balance_min]"
                                    placeholder=" "
                                    value="{{ $role->warnings_active_min }}"
                                />
                                <label
                                    class="form__label form__label--floating"
                                    for="warnings_balance_min"
                                >
                                    Minimum active warnings
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="warnings_balance_min"
                                    class="form__text"
                                    type="text"
                                    name="role[warnings_balance_min]"
                                    placeholder=" "
                                    value="{{ $role->warnings_active_max }}"
                                />
                                <label
                                    class="form__label form__label--floating"
                                    for="warnings_balance_min"
                                >
                                    Maximum active warnings
                                </label>
                            </p>
                        </div>
                    </fieldset>
                </div>
            </form>
        </div>
    </section>
    <div class="dashboard__menus">
        @foreach ([
            'Site' => [
                Permission::MESSAGE_CREATE,
                Permission::COMMENT_CREATE,
                Permission::REQUEST_CREATE,
                Permission::INVITE_CREATE,
                Permission::TORRENT_CREATE
            ],
            'Tracker' =>[
                Permission::ANNOUNCE_PEER_VIEW,
            ]
        ] as $heading => $permissions)
            @include('Staff.role._permission')
        @endforeach
    </div>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                action="{{ route('staff.roles.update', ['role' => $role]) }}"
                method="POST"
            >
                @csrf
                @method('PATCH')
                <p class="form__group form__group--horizontal">
                    <button
                        class="form__button form__button--filled form__button--centered"
                        form="role_edit_form"
                    >
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
