@extends('layout.default')

@section('title')
    <title>WarningLog - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.logs') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('warnings.show', ['username' => $user->username]) }}">
            {{ __('user.warning-log') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('users.bans.index', ['user' => $user]) }}">
            {{ __('user.ban-log') }}
        </a>
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.warnings') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('user.warned-by') }}</th>
                        <th>{{ __('torrent.torrent') }}</th>
                        <th>{{ __('common.reason') }}</th>
                        <th>{{ __('user.created-on') }}</th>
                        <th>{{ __('user.expires-on') }}</th>
                        <th>{{ __('user.active') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warnings as $warning)
                        <tr>
                            <td>
                                <x-user_tag :user="$warning->staffuser" :anon="false" />
                            </td>
                            <td>
                                @isset($warning->torrent)
                                    <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                        {{ $warning->torrenttitle->name }}
                                    </a>
                                @else
                                    n/a
                                @endif
                            </td>
                            <td>{{ $warning->reason }}</td>
                            <td>
                                <time datetime="{{ $warning->created_at }}" title="{{ $warning->created_at }}">
                                    {{ $warning->created_at }}
                                </time>
                            </td>
                            <td>
                                <time datetime="{{ $warning->expires_on }}" title="{{ $warning->expires_on }}">
                                    {{ $warning->expires_on }}
                                </time>
                            </td>
                            <td>
                                @if ($warning->active)
                                    <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                                @else
                                    <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                                @endif
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('deactivateWarning', ['id' => $warning->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to deactivate this warning: {{ $warning->reason }}?',
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
                                                    }
                                                })"
                                                class="form__button form__button--text"
                                                @disabled(! $warning->active)
                                            >
                                                {{ __('user.deactivate') }}
                                            </button>
                                        </form>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('deleteWarning', ['id' => $warning->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this warning: {{ $warning->reason }}?',
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
                            <td colspan="7">{{ __('user.no-warning') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $warnings->links('partials.pagination') }}
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('user.soft-deleted-warnings') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('user.warned-by') }}</th>
                        <th>{{ __('torrent.torrent') }}</th>
                        <th>{{ __('common.reason') }}</th>
                        <th>{{ __('user.created-on') }}</th>
                        <th>{{ __('user.deleted-on') }}</th>
                        <th>{{ __('user.deleted-by') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($softDeletedWarnings as $warning)
                        <tr>
                            <td>
                                <x-user_tag :user="$warning->staffuser" :anon="false" />
                            </td>
                            <td>
                                @isset($warning->torrent)
                                    <a href="{{ route('torrent', ['id' => $warning->torrenttitle->id]) }}">
                                        {{ $warning->torrenttitle->name }}
                                    </a>
                                @else
                                    n/a
                                @endif
                            </td>
                            <td>{{ $warning->reason }}</td>
                            <td>{{ $warning->created_at }}</td>
                            <td>{{ $warning->deleted_at }}</td>
                            <td>
                                <x-user_tag :user="$warning->deletedBy" :anon="false" />
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('restoreWarning', ['id' => $warning->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to restore this warning: {{ $warning->reason }}?',
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
                                                    }
                                                })"
                                                class="form__button form__button--text"
                                                @disabled(! $warning->active)
                                            >
                                                {{ __('user.restore') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">{{ __('user.no-soft-warning') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $softDeletedWarnings->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.actions') }}</h2>
        <dl class="key-value">
            <dt>{{ __('user.warnings') }}</dt>
            <dd>{{ $warningcount }}</dd>
            <dt>{{ __('user.soft-deleted-warnings') }}</dt>
            <dd>{{ $softDeletedWarningCount }}</dd>
        </dl>
        <div class="panel__body">
            <form
                class="form"
                method="POST"
                action="{{ route('massDeactivateWarnings', ['username' => $user->username]) }}"
            >
                @csrf
                <p class="form__group form__group--horizontal">
                    <button class="form__button form__button--filled form__button--centered">
                        {{ __('user.deactivate-all') }}
                    </button>
                </p>
            </form>
            <form
                role="form"
                action="{{ route('massDeleteWarnings', ['username' => $user->username]) }}"
                method="POST"
            >
                @csrf
                @method('DELETE')
                <p class="form__group form__group--horizontal">
                    <button class="form__button form__button--filled form__button--centered">
                        {{ __('user.delete-all') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection
