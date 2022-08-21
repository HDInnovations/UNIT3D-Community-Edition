@extends('layout.default')

@section('title')
    <title>Audits Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Audits Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.audit-log') }}
    </li>
@endsection

@section('page', 'page__audit-log--index')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-list"></i>
            {{ __('staff.audit-log') }}
        </h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('common.action') }}</th>
                    <th>Model</th>
                    <th>Model ID</th>
                    <th>By</th>
                    <th>Changes</th>
                    <th>{{ __('user.created-on') }}</th>
                    <th>{{ __('common.action') }}</th>
                </tr>
                </thead>
                <tbody>
                    @forelse ($audits as $audit)
                        @php $values = json_decode($audit->record, true, 512, JSON_THROW_ON_ERROR) @endphp
                        <tr>
                            <td>{{ $audit->id }}</td>
                            <td>{{ strtoupper($audit->action) }}</td>
                            <td>{{ $audit->model_name }}</td>
                            <td>{{ $audit->model_entry_id }}</td>
                            <td>
                                <a href="{{ route('users.show', ['username' => $audit->user->username]) }}">
                                    {{ $audit->user->username }}
                                </a>
                            </td>
                            <td>
                                <ul>
                                    @foreach ($values as $key => $value)
                                        <li style="word-wrap: break-word; word-break: break-word; overflow-wrap: break-word;">
                                            {{ $key }}:
                                            @if (is_array($value['old']))
                                                @json($value['old'])
                                            @else
                                                {{ $value['old'] ?? 'null' }}
                                            @endif
                                            &rarr;
                                            @if (is_array($value['new']))
                                                @json($value['new'])
                                            @else
                                                {{ $value['new'] ?? 'null' }}
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $audit->created_at }}"
                                    title="{{ $audit->created_at }}"
                                >
                                    {{ $audit->created_at->diffForHumans() }}
                                </time>
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            method="POST"
                                            action="{{ route('staff.audits.destroy', ['id' => $audit->id]) }}"
                                            x-data
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to delete this audit log entry?',
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
                            <td colspan="8">
                                No audits
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $audits->links('partials.pagination') }}
    </section>
@endsection
