@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('rbac.roles') }}
    </li>
@endsection

@section('page', 'page__category--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('rbac.roles') }}</h2>
            <div class="panel__actions">
                <div class="panel__action" x-data="dialog">
                    <button class="form__button form__button--text" x-bind="showDialog">
                        {{ __('common.add') }}
                    </button>
                    <dialog class="dialog" x-bind="dialogElement">
                        <h3 class="dialog__heading">{{ __('rbac.add-role') }}</h3>
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('staff.roles.store') }}"
                            x-bind="dialogForm"
                        >
                            @csrf
                            <p class="form__group">
                                <input
                                    id="name"
                                    class="form__text"
                                    name="name"
                                    placeholder=" "
                                    required
                                    type="text"
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
                                    name="position"
                                    pattern="[0-9]*"
                                    placeholder=" "
                                    required
                                    type="text"
                                />
                                <label class="form__label form__label--floating" for="position">
                                    {{ __('common.position') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <textarea
                                    id="description"
                                    class="form__textarea"
                                    name="description"
                                    required
                                ></textarea>
                                <label class="form__label form__label--floating" for="description">
                                    {{ __('common.description') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.add') }}
                                </button>
                                <button
                                    formmethod="dialog"
                                    formnovalidate
                                    class="form__button form__button--outlined"
                                >
                                    {{ __('common.cancel') }}
                                </button>
                            </p>
                        </form>
                    </dialog>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.position') }}</th>
                        <th>{{ __('common.name') }}</th>
                        <th>{{ __('common.description') }}</th>
                        <th>{{ __('torrent.updated_at') }}</th>
                        <th>{{ __('forum.created-at') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->position }}</td>
                            <td>
                                <a href="{{ route('staff.roles.edit', ['role' => $role]) }}">
                                    {{ $role->name }}
                                </a>
                            </td>
                            <td>{{ $role->description }}</td>
                            <td>
                                <time
                                    datetime="{{ $role->created_at }}"
                                    title="{{ $role->created_at }}"
                                >
                                    {{ $role->created_at }}
                                </time>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $role->updated_at }}"
                                    title="{{ $role->updated_at }}"
                                >
                                    {{ $role->updated_at }}
                                </time>
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('staff.roles.edit', ['role' => $role]) }}"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.roles.destroy', ['role' => $role]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this role: ' . $role->name . '?') }}"
                                                class="form__button form__button--text"
                                            >
                                                {{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
