@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Wikis</li>
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Wikis</h2>
            <div class="panel__actions">
                <a
                    href="{{ route('staff.wikis.create') }}"
                    class="panel__action form__button form__button--text"
                >
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.title') }}</th>
                        <th>{{ __('common.category') }}</th>
                        <th>{{ __('common.created_at') }}</th>
                        <th>{{ __('torrent.updated_at') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wikis as $wiki)
                        <tr>
                            <td>
                                <a href="{{ route('wikis.show', ['wiki' => $wiki]) }}">
                                    {{ $wiki->name }}
                                </a>
                            </td>
                            <td>{{ $wiki->category->name }}</td>
                            <td>{{ $wiki->created_at }}</td>
                            <td>{{ $wiki->updated_at }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            href="{{ route('staff.wikis.edit', ['wiki' => $wiki]) }}"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.wikis.destroy', ['wiki' => $wiki]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this wiki: ' . $wiki->name . '?') }}"
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
                            <td colspan="2">No Wikis</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
