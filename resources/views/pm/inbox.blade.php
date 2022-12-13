@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('inbox') }}" class="breadcrumb__link">
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
                    action="{{ route('searchPMInbox') }}"
                    method="POST"
                >
                    @csrf
                    <p class="form__group">
                        <input
                            id="search"
                            class="form__text"
                            name="subject"
                            required
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
                                <a href="{{ route('message', ['id' => $pm->id]) }}">
                                    {{ $pm->subject }}
                                </a>
                            </td>
                            <td>
                                <time datetime="{{ $pm->created_at }}" title="{{ $pm->created_at }}">
                                    {{ $pm->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                @if ($pm->read == 0)
                                    <i class="{{ \config('other.font-awesome') }} fa-cross text-red" title="{{ __('pm.unread') }}"></i>
                                @else
                                    <i class="{{ \config('other.font-awesome') }} fa-check text-green" title="{{ __('pm.read') }}"></i>
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('delete-pm', ['id' => $pm->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            <button
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
        {{ $pms->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <div class="panel__body">
            <form action="{{ route('mark-all-read') }}" method="POST">
                <p class="form__group form__group--horizontal">
                    @csrf
                    <button class="form__button form__button--filled">
                        <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                        {{ __('pm.mark-all-read') }}
                    </button>
                </p>
            </form>
            <p class="form__group form__group--horizontal">
                <a href="{{ route('inbox') }}" class="form__button form__button--filled">
                    <i class="{{ config('other.font-awesome') }} fa-sync-alt"></i>
                    {{ __('pm.refresh') }}
                </a>
            </p>
            <form method="POST" action="{{ route('empty-inbox') }}" x-data>
                @csrf
                @method('DELETE')
                <p class="form__group form__group--horizontal">
                    <button
                        x-on:click.prevent="Swal.fire({
                            title: 'Are you sure?',
                            text: 'Are you sure you want to delete all private messages?',
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
                        <i class="{{ config('other.font-awesome') }} fa-trash"></i>
                        {{ __('pm.empty-inbox') }}
                    </button>
                </p>
            </form>
        </div>
    </div>
@endsection
