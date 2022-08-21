@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.chat') }} {{ __('staff.statuses') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('staff.statuses.index') }}">
            {{ __('staff.statuses') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.rooms.index') }}">
            {{ __('staff.rooms') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('staff.bots.index') }}">
            {{ __('staff.bots') }}
        </a>
    </li>
@endsection

@section('page', 'page__chat-status--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">
                {{ __('common.user') }} {{ __('staff.chat') }} {{ __('staff.statuses') }}
            </h2>
            <div class="panel__actions">
                <div class="panel__action" x-data>
                    <button class="form__button form__button--text" x-on:click.stop="$refs.dialog.showModal()">
                        {{ __('common.add') }}
                    </button>
                    <dialog class="dialog" x-ref="dialog">
                        <h3 class="dialog__heading">
                            {{ __('common.add') }} {{ __('staff.chat') }} {{ __('staff.status') }}
                        </h3>            
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('staff.statuses.store') }}"
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
                                >
                                <label class="form__label form__label--floating" for="name">
                                    {{ __('common.name') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="color"
                                    class="form__text"
                                    name="color"
                                    required
                                    type="text"
                                >
                                <label class="form__label form__label--floating" for="color">
                                    {{ __('common.color') }} (e.g. #ff0000)
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="icon"
                                    class="form__text"
                                    name="icon"
                                    required
                                    type="text"
                                >
                                <label class="form__label form__label--floating" for="icon">
                                    Font Awesome icon code (e.g. fas fa-comment-smile)
                                </label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--outlined">
                                    {{ __('common.cancel') }}
                                </button>
                                <button class="form__button form__button--filled">
                                    {{ __('common.submit') }}
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
                    <th>Color</th>
                    <th>Icon</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($chatstatuses as $chatstatus)
                    <tr>
                        <td>{{ $chatstatus->id }}</td>
                        <td>{{ $chatstatus->name }}</td>
                        <td>
                            <i
                                class="{{ config('other.font-awesome') }} fa-circle"
                                style="color: {{ $chatstatus->color }};"
                            ></i>
                            {{ $chatstatus->color }}
                        </td>
                        <td>
                            <i class="{{ $chatstatus->icon }}"></i>
                            [{{ $chatstatus->icon }}]
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action" x-data>
                                    <button class="form__button form__button--text" x-on:click.stop="$refs.dialog.showModal()">
                                        {{ __('common.edit') }}
                                    </button>
                                    <dialog class="dialog" x-ref="dialog">
                                        <h3 class="dialog__heading">
                                            {{ __('common.edit') }} Chat Status ({{ $chatstatus->name }})
                                        </h3>
                                        <form
                                            class="dialog__form"
                                            method="POST"
                                            action="{{ route('staff.statuses.update', ['id' => $chatstatus->id]) }}"
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
                                                    value="{{ $chatstatus->name }}"
                                                >
                                                <label class="form__label form__label--floating" for="name">
                                                    {{ __('common.name') }}
                                                </label>
                                            </p>
                                            <p class="form__group">
                                                <input
                                                    id="color"
                                                    class="form__text"
                                                    name="color"
                                                    required
                                                    type="text"
                                                    value="{{ $chatstatus->color }}"
                                                >
                                                <label class="form__label form__label--floating" for="color">
                                                    {{ __('common.color') }} (e.g. #ff0000)
                                                </label>
                                            </p>
                                            <p class="form__group">
                                                <input
                                                    id="icon"
                                                    class="form__text"
                                                    name="icon"
                                                    placeholder="Enter Font Awesome Code Here..."
                                                    required
                                                    type="text"
                                                    value="{{ $chatstatus->icon }}"
                                                >
                                                <label class="form__label form__label--floating" for="icon">
                                                    Font Awesome icon code (e.g. fas fa-comment-smile)
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
                                        action="{{ route('staff.statuses.destroy', ['id' => $chatstatus->id]) }}"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this chat status: {{ $chatstatus->name }}?',
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
