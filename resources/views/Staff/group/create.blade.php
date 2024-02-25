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
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('page', 'page__groups--create')

@section('main')
    <section class="panelV2" x-data="{ autogroup: false }">
        <h2 class="panel__heading">Add New Group</h2>
        <div class="panel__body">
            <form class="form" method="POST" action="{{ route('staff.groups.store') }}">
                @csrf
                <p class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        type="text"
                        name="group[name]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('common.name') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="position"
                        class="form__text"
                        type="text"
                        name="group[position]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="position">
                        {{ __('common.position') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="level"
                        class="form__text"
                        type="text"
                        name="group[level]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="level">Level</label>
                </p>
                <p class="form__group">
                    <input
                        id="download_slots"
                        class="form__text"
                        type="text"
                        name="group[download_slots]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="download_slots">
                        DL Slots
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="color"
                        class="form__text"
                        type="text"
                        name="group[color]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="color">
                        Color (e.g. #ff0000)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="icon"
                        class="form__text"
                        type="text"
                        name="group[icon]"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="icon">
                        FontAwesome Icon (e.g. fas fa-user)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="effect"
                        class="form__text"
                        type="text"
                        name="group[effect]"
                        value="none"
                        placeholder="GIF Effect"
                    />
                    <label class="form__label form__label--floating" for="effect">
                        Effect (e.g. url(/img/sparkels.gif))
                    </label>
                </p>
                <p class="form__group">
                    <input name="group[is_internal]" type="hidden" value="0" />
                    <input
                        id="is_internal"
                        class="form__checkbox"
                        name="group[is_internal]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_internal">Internal</label>
                </p>
                <p class="form__group">
                    <input name="group[is_editor]" type="hidden" value="0" />
                    <input
                        id="is_editor"
                        class="form__checkbox"
                        name="group[is_editor]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_editor">Editor</label>
                </p>
                <p class="form__group">
                    <input name="group[is_modo]" type="hidden" value="0" />
                    <input
                        id="is_modo"
                        class="form__checkbox"
                        name="group[is_modo]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_modo">Modo</label>
                </p>
                <p class="form__group">
                    <input name="group[is_admin]" type="hidden" value="0" />
                    <input
                        id="is_admin"
                        class="form__checkbox"
                        name="group[is_admin]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_admin">Admin</label>
                </p>
                <p class="form__group">
                    <input name="group[is_owner]" type="hidden" value="0" />
                    <input
                        id="is_owner"
                        class="form__checkbox"
                        name="group[is_owner]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_owner">Owner</label>
                </p>
                <p class="form__group">
                    <input name="group[is_trusted]" type="hidden" value="0" />
                    <input
                        id="is_trusted"
                        class="form__checkbox"
                        name="group[is_trusted]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_trusted">Trusted</label>
                </p>
                <p class="form__group">
                    <input name="group[is_immune]" type="hidden" value="0" />
                    <input
                        id="is_immune"
                        class="form__checkbox"
                        name="group[is_immune]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_immune">Immune</label>
                </p>
                <p class="form__group">
                    <input name="group[is_freeleech]" type="hidden" value="0" />
                    <input
                        id="is_freeleech"
                        class="form__checkbox"
                        name="group[is_freeleech]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_freeleech">Freeleech</label>
                </p>
                <p class="form__group">
                    <input name="group[is_double_upload]" type="hidden" value="0" />
                    <input
                        id="is_double_upload"
                        class="form__checkbox"
                        name="group[is_double_upload]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_double_upload">Double Upload</label>
                </p>
                <p class="form__group">
                    <input name="group[is_refundable]" type="hidden" value="0" />
                    <input
                        id="is_refundable"
                        class="form__checkbox"
                        name="group[is_refundable]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_refundable">Refundable Download</label>
                </p>
                <p class="form__group">
                    <input name="group[is_incognito]" type="hidden" value="0" />
                    <input
                        id="is_incognito"
                        class="form__checkbox"
                        name="group[is_incognito]"
                        type="checkbox"
                        value="1"
                    />
                    <label class="form__label" for="is_incognito">Incognito</label>
                </p>
                <div class="form__group">
                    <fieldset class="form__fieldset">
                        <legend class="form__legend">{{ __('rbac.roles') }}</legend>
                        <div class="form__fieldset-checkbox-container--expand">
                            @foreach ($roles as $role)
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                            id="{{ $role->name }}"
                                            class="form__checkbox"
                                            name="roles[]"
                                            type="checkbox"
                                            value="{{ $role->id }}"
                                            @checked($role->group_roles_exist)
                                        />
                                        {{ $role->name }}
                                    </label>
                                </p>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
                <p class="form__group">
                    <input name="group[autogroup]" type="hidden" value="0" />
                    <input
                        id="autogroup"
                        class="form__checkbox"
                        name="group[autogroup]"
                        type="checkbox"
                        x-model="autogroup"
                        value="1"
                    />
                    <label class="form__label" for="autogroup">Autogroup</label>
                </p>
                <div class="form__group" x-show="autogroup" x-cloak>
                    <fieldset class="form form__fieldset">
                        <legend class="form__legend">Autogroup requirements</legend>
                        <p class="form__group">
                            <input
                                id="min_uploaded"
                                class="form__text"
                                type="text"
                                name="group[min_uploaded]"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="min_uploaded">
                                Minimum upload required
                            </label>
                        </p>
                        <p class="form__group" x-show="autogroup" x-cloak>
                            <input
                                id="min_ratio"
                                class="form__text"
                                type="text"
                                name="group[min_ratio]"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="min_ratio">
                                Minimum ratio required
                            </label>
                        </p>
                        <p class="form__group" x-show="autogroup" x-cloak>
                            <input
                                id="minimum_age"
                                class="form__text"
                                type="text"
                                name="group[minimum_age]"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="minimum_age">
                                Minimum age required
                            </label>
                        </p>
                        <p class="form__group" x-show="autogroup" x-cloak>
                            <input
                                id="min_avg_seedtime"
                                class="form__text"
                                type="text"
                                name="group[min_avg_seedtime]"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="min_avg_seedtime">
                                Minimum average seedtime required
                            </label>
                        </p>
                        <p class="form__group" x-show="autogroup" x-cloak>
                            <input
                                id="min_seedsize"
                                class="form__text"
                                type="text"
                                name="group[min_seedsize]"
                                placeholder=" "
                            />
                            <label class="form__label form__label--floating" for="min_seedsize">
                                Minimum seedsize required
                            </label>
                        </p>
                    </fieldset>
                </div>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.add') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
