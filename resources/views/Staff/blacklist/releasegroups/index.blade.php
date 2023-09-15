@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" itemprop="url" class="breadcrumb__link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Blacklists</span>
        </a>
    </li>
    <li class="breadcrumb--active">
        Release Groups
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Blacklist Release Groups</h2>
            <div class="panel__actions">
                <a 
                    class="panel__action form__button form__button--text"
                    href="{{ route('staff.blacklisted_releasegroups.create') }}"
                >
                    {{ __('common.add') }}
                </a>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th>{{ __('common.name') }}</th>
                        <th>Forbidden types</th>
                        <th>{{ __('common.reason') }}</th>
                        <th>Created at</th>
                        <th width="15%">{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($releasegroups as $releasegroup)
                        <tr>
                            <td>
                                {{ $releasegroup->id }}
                            </td>
                            <td>
                                {{ $releasegroup->name }}
                            </td>
                            <td>
                                @if (!is_array($releasegroup->object_releasegroup->types ?? ''))
                                    ALL
                                @else
                                    @foreach ($types as $type)
                                        @if (is_array($releasegroup->object_releasegroup->types ?? '') && in_array((string)$type->id, $releasegroup->object_releasegroup->types ?? '', true))
                                            {{ $type->name }},
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td style="word-wrap:break-word;">
                                {{ $releasegroup->reason }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($releasegroup->created_at)->format('Y-m-d')}}
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            href="{{ route('staff.blacklisted_releasegroups.edit', ['blacklistReleasegroup' => $releasegroup->id]) }}"
                                            class="form__button form__button--text"
                                        >
                                            {{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.blacklisted_releasegroups.destroy', ['blacklistReleasegroup' => $releasegroup->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
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
