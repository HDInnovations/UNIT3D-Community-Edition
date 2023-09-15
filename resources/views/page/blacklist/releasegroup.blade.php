@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumb--active">
        Releasegroup Blacklist
    </li>
@endsection

@section('page', 'page__blacklist--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Release Groups</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Forbidden types</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($releasegroups as $releasegroup)
                        <tr>
                            <td>{{ $releasegroup->name }}</td>
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
                            <td>{{ $releasegroup->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <div class="panel__body">
            The Following Release Groups Are Blacklisted/Forbidden On {{ config('other.title') }},
            While Some Of Them May Only Have Releases Of A Certain Type Blacklisted.
        </div>
    </section>
@endsection
