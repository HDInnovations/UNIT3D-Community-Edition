@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Client Blacklist</li>
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Blacklisted Clients</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <a
                        href="{{ route('staff.blacklisted_clients.create') }}"
                        class="form__button form__button--text"
                    >
                        {{ __('common.add') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Peer ID prefix</th>
                        <th>{{ __('common.reason') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->reason }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            href="{{ route('staff.blacklisted_clients.edit', ['blacklistClient' => $client]) }}"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.blacklisted_clients.destroy', ['blacklistClient' => $client]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="
                                                    Swal.fire({
                                                        title: 'Delete?',
                                                        text: 'Are you sure you want to delete?',
                                                        icon: 'warning',
                                                        showConfirmButton: true,
                                                        showCancelButton: true,
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $root.submit();
                                                        }
                                                    })
                                                "
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
