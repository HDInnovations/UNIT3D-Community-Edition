@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.groups.index') }}" class="breadcrumb__link">
            {{ __('staff.groups') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        {{ $group->name }}
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__groups--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Edit Group: {{ $group->name }}</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('staff.groups.update', ['group' => $group->name, 'id' => $group->id]) }}">
                @csrf
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="name"
                        placeholder=""
                        value="{{ $group->name }}"
                    />
                    <label class="form__label form__label--floating">
                        {{ __('common.name') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="position"
                        placeholder=""
                        value="{{ $group->position }}"
                    />
                    <label class="form__label form__label--floating">
                        {{ __('common.position') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="level"
                        placeholder=""
                        value="{{ $group->level }}"
                    />
                    <label class="form__label form__label--floating">
                        Level
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="download_slots"
                        placeholder=""
                        value="{{ $group->download_slots }}"
                    />
                    <label class="form__label form__label--floating">
                        DL Slots
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="color"
                        placeholder=""
                        value="{{ $group->color }}"
                    />
                    <label class="form__label form__label--floating">
                        Color (e.g. #ff0000)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="icon"
                        placeholder=""
                        value="{{ $group->icon }}"
                    />
                    <label class="form__label form__label--floating">
                        FontAwesome Icon (e.g. fas fa-user)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        class="form__text"
                        type="text"
                        name="effect"
                        placeholder="GIF Effect"
                        value="{{ $group->effect }}"
                    />
                    <label class="form__label form__label--floating">
                        Effect (e.g. url(/img/sparkels.gif))
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_internal" type="hidden" value="0">
                    <input
                        id="is_internal"
                        class="form__checkbox"
                        name="is_internal"
                        type="checkbox"
                        value="1"
                        @checked($group->is_internal)
                    >
                    <label class="form__label" for="is_internal">
                        Internal
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_modo" type="hidden" value="0">
                    <input
                        id="is_modo"
                        class="form__checkbox"
                        name="is_modo"
                        type="checkbox"
                        value="1"
                        @checked($group->is_modo)
                    >
                    <label class="form__label" for="is_modo">
                        Modo
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_admin" type="hidden" value="0">
                    <input
                        id="is_admin"
                        class="form__checkbox"
                        name="is_admin"
                        type="checkbox"
                        value="1"
                        @checked($group->is_admin)
                    >
                    <label class="form__label" for="is_admin">
                        Admin
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_owner" type="hidden" value="0">
                    <input
                        id="is_owner"
                        class="form__checkbox"
                        name="is_owner"
                        type="checkbox"
                        value="1"
                        @checked($group->is_owner)
                    >
                    <label class="form__label" for="is_owner">
                        Owner
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_trusted" type="hidden" value="0">
                    <input
                        id="is_trusted"
                        class="form__checkbox"
                        name="is_trusted"
                        type="checkbox"
                        value="1"
                        @checked($group->is_trusted)
                    >
                    <label class="form__label" for="is_trusted">
                        Trusted
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_immune" type="hidden" value="0">
                    <input
                        id="is_immune"
                        class="form__checkbox"
                        name="is_immune"
                        type="checkbox"
                        value="1"
                        @checked($group->is_immune)
                    >
                    <label class="form__label" for="is_immune">
                        Immune
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_freeleech" type="hidden" value="0">
                    <input
                        id="is_freeleech"
                        class="form__checkbox"
                        name="is_freeleech"
                        type="checkbox"
                        value="1"
                        @checked($group->is_freeleech)
                    >
                    <label class="form__label" for="is_freeleech">
                        Freeleech
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_double_upload" type="hidden" value="0">
                    <input
                        id="is_double_upload"
                        class="form__checkbox"
                        name="is_double_upload"
                        type="checkbox"
                        value="1"
                        @checked($group->is_double_upload)
                    >
                    <label class="form__label" for="is_double_upload">
                        Double Upload
                    </label>
                </p>
                <p class="form__group">
                    <input name="is_incognito" type="hidden" value="0">
                    <input
                        id="is_incognito"
                        class="form__checkbox"
                        name="is_incognito"
                        type="checkbox"
                        value="1"
                        @checked($group->is_incognito)
                    >
                    <label class="form__label" for="is_incognito">
                        Incognito
                    </label>
                </p>
                <p class="form__group">
                    <input name="can_upload" type="hidden" value="0">
                    <input
                        id="can_upload"
                        class="form__checkbox"
                        name="can_upload"
                        type="checkbox"
                        value="1"
                        @checked($group->can_upload)
                    >
                    <label class="form__label" for="can_upload">
                        Upload
                    </label>
                </p>
                <p class="form__group">
                    <input name="autogroup" type="hidden" value="0">
                    <input
                        id="autogroup"
                        class="form__checkbox"
                        name="autogroup"
                        type="checkbox"
                        value="1"
                        @checked($group->autogroup)
                    >
                    <label class="form__label" for="autogroup">
                        Autogroup
                    </label>
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
