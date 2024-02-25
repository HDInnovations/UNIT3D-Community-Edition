@extends('layout.default')

@section('title')
    <title>
        {{ __('common.user') }} {{ __('common.edit') }} - {{ __('staff.staff-dashboard') }} -
        {{ config('other.title') }}
    </title>
@endsection

@section('meta')
    <meta
        name="description"
        content="User {{ __('common.edit') }} - {{ __('staff.staff-dashboard') }}"
    />
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.users.index') }}" class="breadcrumb__link">
            {{ __('common.users') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__users--edit')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.account') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.users.update', ['user' => $user]) }}"
            >
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <input
                        id="username"
                        class="form__text"
                        name="user[username]"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->username }}"
                    />
                    <label class="form__label form__label--floating" for="username">
                        {{ __('common.username') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="uploaded"
                        class="form__text"
                        inputmode="numeric"
                        name="user[uploaded]"
                        pattern="[0-9]{1,}"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->uploaded }}"
                    />
                    <label class="form__label form__label--floating" for="uploaded">
                        {{ __('user.total-upload') }} (Bytes)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="downloaded"
                        class="form__text"
                        inputmode="numeric"
                        name="user[downloaded]"
                        pattern="[0-9]{1,}"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->downloaded }}"
                    />
                    <label class="form__label form__label--floating" for="downloaded">
                        {{ __('user.total-download') }} (Bytes)
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="title"
                        class="form__text"
                        name="user[title]"
                        placeholder=" "
                        type="text"
                        value="{{ $user->title }}"
                    />
                    <label class="form__label form__label--floating" for="title">
                        {{ __('user.title') }}
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'user[about]', 'label' => __('user.about-me'), 'required' => false, 'content' => $user->about])
                <p class="form__group">
                    <select id="group_id" class="form__select" name="user[group_id]">
                        <option class="form__option" value="{{ $user->group->id }}">
                            {{ $user->group->name }} (Default)
                        </option>
                        @foreach ($groups as $group)
                            <option class="form__option" value="{{ $group->id }}">
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="group_id">
                        {{ __('common.group') }}
                    </label>
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
                                            @checked($user->roles->contains($role))
                                        />
                                        {{ $role->name }}
                                    </label>
                                </p>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
                <p class="form__group">
                    <input
                        id="seedbonus"
                        class="form__text"
                        inputmode="numeric"
                        name="user[seedbonus]"
                        pattern="[0-9]{1,}(?:.[0-9]{1,2})?"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->seedbonus }}"
                    />
                    <label class="form__label form__label--floating" for="seedbonus">
                        {{ __('bon.bon') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="fl_tokens"
                        class="form__text"
                        inputmode="numeric"
                        name="user[fl_tokens]"
                        pattern="[0-9]{1,}"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->fl_tokens }}"
                    />
                    <label class="form__label form__label--floating" for="fl_tokens">
                        {{ __('torrent.freeleech-token') }}
                    </label>
                </p>
                <p class="form__group">
                    <input
                        id="invites"
                        class="form__text"
                        inputmode="numeric"
                        name="user[invites]"
                        pattern="[0-9]{1,}"
                        placeholder=" "
                        required
                        type="text"
                        value="{{ $user->invites }}"
                    />
                    <label class="form__label form__label--floating" for="invites">
                        {{ __('user.invites') }}
                    </label>
                </p>
                <p class="form__group">
                    <button class="form__button form__button--filled">
                        {{ __('common.save') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
