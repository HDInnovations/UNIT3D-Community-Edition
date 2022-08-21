@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.chat-rooms') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.statuses.index') }}">
            {{ __('staff.statuses') }}
        </a>
    </li>
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('staff.rooms.index') }}">
            {{ __('staff.rooms') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.bots.index') }}">
            {{ __('staff.bots') }}
        </a>
    </li>
@endsection

@section('page', 'page__chat-room--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('common.chat-rooms') }}</h2>
            <div class="panel__actions">
                <div class="panel__action" x-data>
                    <button class="form__button form__button--text" x-on:click.stop="$refs.dialog.showModal()">
                        {{ __('common.add') }}
                    </button>
                    <dialog class="dialog" x-ref="dialog">
                        <h3 class="dialog__heading">{{ __('common.add') }} {{ __('common.chat-room') }}</h2>
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('staff.rooms.store') }}"
                            x-on:click.outside="$refs.dialog.close()"
                        >
                            @csrf
                            <p class="form__group">
                                <input
                                    type="text"
                                    class="form__text"
                                    name="name"
                                    id="name"
                                    required
                                >
                                <label class="form__label form__label--floating" for="chatroom_name">{{ __('common.name') }}</label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.submit') }}
                                </button>
                                <button x-on:click.prevent="$refs.dialog.close()" class="form__button form__button--outlined">
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
                    <th>ID</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($chatrooms as $chatroom)
                    <tr>
                        <td>{{ $chatroom->id }}</td>
                        <td>{{ $chatroom->name }}</td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action" x-data>
                                    <button class="form__button form__button--text" x-on:click.stop="$refs.dialog.showModal()">
                                        {{ __('common.edit') }}
                                    </button>
                                    <dialog class="dialog" x-ref="dialog">
                                        <h3 class="dialog__heading">
                                            {{ __('common.edit') }} {{ __('common.chat-room') }} ({{ $chatroom->name }})
                                        </h3>
                                        <form
                                            class="dialog__form"
                                            method="POST"
                                            action="{{ route('staff.rooms.update', ['id' => $chatroom->id]) }}"
                                            x-on:click.outside="$refs.dialog.close()"
                                        >
                                            @csrf
                                            <p class="form__group">
                                                <input
                                                    id="name"
                                                    class="form__text"
                                                    name="name"
                                                    required
                                                    type="text"
                                                    value="{{ $chatroom->name }}"
                                                >
                                                <label class="form__label form__label--floating" for="chatroom_name">
                                                    {{ __('common.name') }}
                                                </label>
                                            </p>
                                            <p class="form__group">
                                                <button class="form__button form__button--filled">
                                                    {{ __('common.submit') }}
                                                </button>
                                                <button x-on:click.prevent="$refs.dialog.close()" class="form__button form__button--outlined">
                                                    {{ __('common.cancel') }}
                                                </button>
                                            </p>
                                        </form>
                                    </dialog>
                                </li>
                                <li class="data-table__action">
                                    <form
                                        method="POST"
                                        action="{{ route('staff.rooms.destroy', ['id' => $chatroom->id]) }}"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this chatroom: {{ $chatroom->name }}?',
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
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
