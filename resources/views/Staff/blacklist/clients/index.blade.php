@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Client Blacklist
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Blacklisted Clients</h2>
            <div class="panel__actions">
                <a href="{{ route('staff.blacklists.clients.create') }}" class="panel__action">
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ __('common.name') }}</th>
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
                            <form x-data action="{{ route('staff.blacklists.clients.destroy', ['id' => $client->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('staff.blacklists.clients.edit', ['id' => $client->id]) }}"
                                   class="form__button form__button--filled">
                                    {{ __('common.edit') }}
                                </a>
                                <button
                                        x-on:click.prevent="Swal.fire({
                                        title: 'Delete?',
                                        text: 'Are you sure you want to delete?',
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
                                    {{ __('common.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection