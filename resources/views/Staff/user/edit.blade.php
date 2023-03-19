@extends('layout.default')

@section('title')
    <title>{{ __('common.user') }} {{ __('common.edit') }} - {{ __('staff.staff-dashboard') }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="User {{ __('common.edit') }} - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('user_search') }}" class="breadcrumb__link">
            {{ __('common.users') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('page', 'page__users--edit')

@section('main')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(max(320px, 45%), 1fr)); gap: 1rem;">
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('common.account') }}</h2>
                <div class="panel__body">
                    <form
                        class="form"
                        method="POST"
                        action="{{ route('user_edit', ['username' => $user->username]) }}"
                    >
                        @csrf
                        <p class="form__group">
                            <input
                                id="username"
                                class="form__text"
                                name="username"
                                required
                                type="text"
                                value="{{ $user->username }}"
                            >
                            <label class="form__label form__label--floating" for="username">
                                {{ __('common.username') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <input
                                id="email"
                                class="form__text"
                                name="email"
                                required
                                type="email"
                                value="{{ $user->email }}"
                            >
                            <label class="form__label form__label--floating" for="email">
                                {{ __('common.email') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <input
                                id="uploaded"
                                class="form__text"
                                inputmode="numeric"
                                name="uploaded"
                                pattern="[0-9]*"
                                required
                                type="text"
                                value="{{ $user->uploaded }}"
                            >
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
                                pattern="[0-9]*"
                                required
                                type="text"
                                value="{{ $user->downloaded }}"
                            >
                            <label class="form__label form__label--floating" for="downloaded">
                                {{ __('user.total-download') }} (Bytes)
                            </label>
                        </p>
                        <p class="form__group">
                            <input
                                id="title"
                                class="form__text"
                                name="title"
                                placeholder=""
                                type="text"
                                value="{{ $user->title }}"
                            >
                            <label class="form__label form__label--floating" for="title">
                                {{ __('user.title') }}
                            </label>
                        </p>
                        @livewire('bbcode-input', ['name' => 'about', 'label' => __('user.about-me'), 'required' => false, 'content' => $user->about])
                        <p class="form__group">
                            <select
                                id="group_id"
                                class="form__select"
                                name="group_id"
                            >
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
                                <select
                                    id="internal_id"
                                    name="internal_id"
                                    class="form__select"
                                >
                                    @if ($user->internal != null)
                                        <option class="form__option" value="{{ $user->internal->id }}">
                                            {{ $user->internal->name }} (Default)
                                        </option>
                                    @endif
                                    <option class="form__option" value="">None</option>
                                    @foreach ($internals as $internal)
                                        <option class="form__option" value="{{ $internal->id }}">
                                            {{ $internal->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="form__label form__label--floating" for="internal_id">
                                    Internal Group
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
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.change-password') }}</h2>
                <div class="panel__body">
                    <form
                        class="form"
                        method="POST"
                        action="{{ route('user_password', ['username' => $user->username]) }}"
                        x-data
                    >
                        @csrf
                        <p class="form__group">
                            <input
                                id="new_password"
                                class="form__text"
                                minlength="6"
                                name="new_password"
                                type="password"
                                autocomplete="new-password"
                                placeholder=""
                            >
                            <label class="form__label form__label--floating" for="new_password">
                                {{ __('common.password') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <button
                                x-on:click.prevent="Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'Are you sure you want to change this user\'s password?',
                                    icon: 'warning',
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $root.submit();
                                    }
                                })"
                                class="form__button form__button--filled"
                            >
                                {{ __('common.save') }}
                            </button>
                        </p>
                    </form>
                </div>
            </section>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('user.id-permissions') }}</h2>
                <div class="panel__body">
                    <form
                        class="form"
                        method="POST"
                        action="{{ route('user_permissions', ['username' => $user->username]) }}"
                    >
                        @csrf
                        <p class="form__group">
                            <input type="hidden" name="can_upload" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_upload"
                                name="can_upload"
                                value="1"
                                @checked($user->can_upload)
                            >
                            <label for="can_upload">{{ __('user.can-upload') }}?</label>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="can_download" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_download"
                                name="can_download"
                                value="1"
                                @checked($user->can_download)
                            >
                            <label for="can_download">{{ __('user.can-download') }}?</label>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="can_comment" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_comment"
                                name="can_comment"
                                value="1"
                                @checked($user->can_comment)
                            >
                            <label for="can_comment">{{ __('user.can-comment') }}?</label>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="can_invite" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_invite"
                                name="can_invite"
                                value="1"
                                @checked($user->can_invite)
                            >
                            <label for="can_invite">{{ __('user.can-invite') }}?</label>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="can_request" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_request"
                                name="can_request"
                                value="1"
                                @checked($user->can_request)
                            >
                            <label for="can_request">{{ __('user.can-request') }}?</label>
                        </p>
                        <p class="form__group">
                            <input type="hidden" name="can_chat" value="0">
                            <input
                                type="checkbox"
                                class="form__checkbox"
                                id="can_chat"
                                name="can_chat"
                                value="1"
                                @checked($user->can_chat)
                            >
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
            <section class="panelV2">
                <h2 class="panel__heading">{{ __('staff.user-notes') }}</h2>
                <div class="panel__body">
                    <form
                        class="form"
                        method="POST"
                        action="{{ route('staff.notes.store', ['username' => $user->username]) }}"
                    >
                        @csrf
                        <p class="form__group">
                            <textarea
                                id="message"
                                class="form__textarea"
                                name="message"
                                placeholder=""
                            ></textarea>
                            <label class="form__label form__label--floating" for="message">
                                {{ __('staff.user-notes') }}
                            </label>
                        </p>
                        <p class="form__group">
                            <button class="form__button form__button--filled">
                                {{ __('common.save') }}
                            </button>
                        </p>
                    </form>
                </div>
                <div class="data-table-wrapper">
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>{{ __('common.staff') }}</th>
                            <th>{{ __('user.note') }}</th>
                            <th>{{ __('user.created-on') }}</th>
                            <th>{{ __('common.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($notes as $note)
                                <tr>
                                    <td>
                                        <x-user_tag :anon="false" :user="$note->staffuser" />
                                    </td>
                                    <td>{{ $note->message }}</td>
                                    <td>
                                        <time datetime="{{ $note->created_at }}" title="{{ $note->created_at }}">
                                            {{ $note->created_at->diffForHumans() }}
                                        </time>
                                    </td>
                                    <td>
                                        <menu class="data-table__actions">
                                            <li class="data-table__action">
                                                <form
                                                    action="{{ route('staff.notes.destroy', ['id' => $note->id]) }}"
                                                    method="POST"
                                                    x-data
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        x-on:click.prevent="Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: 'Are you sure you want to delete this note?: {{ $note->message }}',
                                                            icon: 'warning',
                                                            showConfirmButton: true,
                                                            showCancelButton: true,
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                $root.submit();
                                                            }
                                                        })"
                                                        class="form__button form__button--text"
                                                    >
                                                        {{ __('common.delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </menu>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No notes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection