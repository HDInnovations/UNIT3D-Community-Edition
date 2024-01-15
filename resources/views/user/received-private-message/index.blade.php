@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a
            href="{{ route('users.received_messages.index', ['user' => $user]) }}"
            class="breadcrumb__link"
        >
            {{ __('pm.messages') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('pm.inbox') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.pmmenu')
@endsection

@section('page', 'page__pm--inbox')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('pm.inbox') }}</h2>
            <div class="panel__actions">
                <form
                    class="panel__action"
                    action="{{ route('users.received_messages.index', ['user' => $user]) }}"
                >
                    <p class="form__group">
                        <input
                            id="search"
                            class="form__text"
                            name="subject"
                            value="{{ $subject }}"
                        />
                        <label class="form__label form__label--floating" for="search">
                            {{ __('pm.search') }}
                        </label>
                    </p>
                </form>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('pm.from') }}</th>
                        <th>{{ __('pm.subject') }}</th>
                        <th>{{ __('pm.received-at') }}</th>
                        <th>{{ __('pm.read') }}</th>
                        <th>{{ __('pm.delete') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pms as $pm)
                        <tr>
                            <td>
                                <x-user_tag :user="$pm->sender" :anon="false" />
                            </td>
                            <td>
                                <a
                                    href="{{ route('users.received_messages.show', ['user' => $user, 'receivedPrivateMessage' => $pm]) }}"
                                >
                                    {{ $pm->subject }}
                                </a>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $pm->created_at }}"
                                    title="{{ $pm->created_at }}"
                                >
                                    {{ $pm->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                @if ($pm->read == 0)
                                    <i
                                        class="{{ \config('other.font-awesome') }} fa-times text-red"
                                        title="{{ __('pm.unread') }}"
                                    ></i>
                                @else
                                    <i
                                        class="{{ \config('other.font-awesome') }} fa-check text-green"
                                        title="{{ __('pm.read') }}"
                                    ></i>
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('users.received_messages.destroy', ['user' => $user, 'receivedPrivateMessage' => $pm]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button class="form__button form__button--text">
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
        {{ $pms->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            <form
                action="{{ route('users.received_messages.mass_update', ['user' => $user]) }}"
                method="POST"
            >
                <p class="form__group form__group--horizontal">
                    @csrf
                    @method('PATCH')
                    <button class="form__button form__button--filled form__button--centered">
                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                        {{ __('pm.mark-all-read') }}
                    </button>
                </p>
            </form>
            <form
                method="POST"
                action="{{ route('users.received_messages.mass_destroy', ['user' => $user]) }}"
                x-data="confirmation"
            >
                @csrf
                @method('DELETE')
                <p class="form__group form__group--horizontal">
                    <button
                        x-on:click.prevent="confirmAction"
                        data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete all private messages?') }}"
                        class="form__button form__button--filled form__button--centered"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                        {{ __('pm.empty-inbox') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
