@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Internals
    </li>
@endsection

@section('page', 'page__internals--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Internal Groups</h2>
            <div class="panel__actions">
                <a
                    href="{{ route('staff.internals.create') }}"
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
                    <th>ID</th>
                    <th>{{ __('common.name') }}</th>
                    <th>Icon</th>
                    <th>Effect</th>
                    <th width="15%">{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($internals as $internal)
                    <tr>
                        <td>{{ $internal->id }}</td>
                        <td>
                            <a href="{{ route('staff.internals.edit', ['id' => $internal->id]) }}">
                                {{ $internal->name }}
                            </a>
                        </td>
                        <td>{{ $internal->icon }}</td>
                        <td>{{ $internal->effect }}</td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <a
                                        href="{{ route('staff.internals.edit', ['id' => $internal->id]) }}"
                                        class="form__button form__button--text"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </li>
                                <li class="data-table__action">
                                    <form
                                        method="POST"
                                        action="{{ route('staff.internals.destroy', ['id' => $internal->id]) }}"
                                        x-data
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            x-on:click.prevent="Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'Are you sure you want to delete this internal group: {{ $internal->name }}?',
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
