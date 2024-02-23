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
                        name="username"
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
                        name="uploaded"
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
                        name="downloaded"
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
                        name="title"
                        placeholder=" "
                        type="text"
                        value="{{ $user->title }}"
                    />
                    <label class="form__label form__label--floating" for="title">
                        {{ __('user.title') }}
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'about', 'label' => __('user.about-me'), 'required' => false, 'content' => $user->about])
                <p class="form__group">
                    <select id="group_id" class="form__select" name="group_id">
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
                <p class="form__group">
                    <input
                        id="seedbonus"
                        class="form__text"
                        inputmode="numeric"
                        name="seedbonus"
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
                        name="fl_tokens"
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
                        name="invites"
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

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.id-permissions') }}</h2>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('staff.users.update_permissions', ['user' => $user]) }}"
            >
                @csrf
                @method('PATCH')
                <p class="form__group">
                    <input type="hidden" name="can_upload" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_upload"
                        name="can_upload"
                        value="1"
                        @checked($user->can_upload)
                    />
                    <label for="can_upload">{{ __('user.can-upload') }}?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="can_download" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_download"
                        name="can_download"
                        value="1"
                        @checked($user->can_download)
                    />
                    <label for="can_download">{{ __('user.can-download') }}?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="can_comment" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_comment"
                        name="can_comment"
                        value="1"
                        @checked($user->can_comment)
                    />
                    <label for="can_comment">{{ __('user.can-comment') }}?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="can_invite" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_invite"
                        name="can_invite"
                        value="1"
                        @checked($user->can_invite)
                    />
                    <label for="can_invite">{{ __('user.can-invite') }}?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="can_request" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_request"
                        name="can_request"
                        value="1"
                        @checked($user->can_request)
                    />
                    <label for="can_request">{{ __('user.can-request') }}?</label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="can_chat" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="can_chat"
                        name="can_chat"
                        value="1"
                        @checked($user->can_chat)
                    />
                    <label for="can_chat">{{ __('user.can-chat') }}?</label>
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
