@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - {{ __('user.seedboxes') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.seedboxes') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__user-seedbox--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('user.seedboxes') }}</h2>
            <div class="panel__actions">
                <div class="panel__action" x-data="{ open: false }">
                    <button class="form__button form__button--text" x-on:click.stop="open = true; $refs.dialog.showModal();">
                        {{ __(('common.add')) }}
                    </button>
                    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
                        <h3 class="dialog__heading">
                            {{ __('user.add-seedbox') }}
                        </h3>
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('seedboxes.store', ['username' => $user->username]) }}"
                            x-on:click.outside="open = false; $refs.dialog.close();"
                        >
                            @csrf
                            <p class="form__group">
                                <input
                                    id="name"
                                    class="form__text"
                                    name="name"
                                    required
                                >
                                <label for="name" class="form__label form__label--floating">
                                    {{ __('common.name') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <input
                                    id="ip"
                                    class="form__text"
                                    name="ip"
                                    required
                                    minlength="7"
                                    maxlength="15"
                                    pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$"
                                >
                                <label for="ip" class="form__label form__label--floating">
                                    {{ __('user.client-ip-address') }}
                                </label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.submit') }}
                                </button>
                                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
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
                <tr>
                    <th>{{ __('torrent.agent') }}</th>
                    <th>IP</th>
                    <th>{{ __('common.added') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
                @foreach ($seedboxes as $seedbox)
                    <tr>
                        <td>{{ $seedbox->name }}</td>
                        <td>{{ $seedbox->ip }}</td>
                        <td>
                            <time datetime="{{ $seedbox->created_at }}" title="{{ $seedbox->created_at }}">
                                {{ $seedbox->created_at->diffForHumans() }}
                            </time>
                        </td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <form
                                        method="POST"
                                        action="{{ route('seedboxes.destroy', ['id' => $seedbox->id]) }}"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this seedbox: {{ $seedbox->name }}?',
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
            </table>
            {{ $seedboxes->links('partials.pagination') }}
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i>
            {{ strtoupper(__('user.disclaimer')) }}
        </h2>
        <div class="panel__body">
            {{ __('user.disclaimer-info') }}
            <br>
            <br>
            {{ __('user.disclaimer-info-bordered') }}
        </div>
    </section>
@endsection
