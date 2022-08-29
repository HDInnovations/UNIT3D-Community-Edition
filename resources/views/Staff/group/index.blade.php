@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('staff.groups') }}
    </li>
@endsection

@section('page', 'page__groups--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Groups</h2>
            <div class="panel__actions">
                <a
                    href="{{ route('staff.groups.create') }}"
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
                    <th>{{ __('common.position') }}</th>
                    <th>Level</th>
                    <th>DL Slots</th>
                    <th>Color</th>
                    <th>Icon</th>
                    <th>Effect</th>
                    <th>Internal</th>
                    <th>Modo</th>
                    <th>Admin</th>
                    <th>Owner</th>
                    <th>Trusted</th>
                    <th>Immune</th>
                    <th>Freeleech</th>
                    <th>Double Upload</th>
                    <th>Incognito</th>
                    <th>Upload</th>
                    <th>Autogroup</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->id }}</td>
                        <td>
                            <a href="{{ route('staff.groups.edit', ['id' => $group->id]) }}">
                                {{ $group->name }}
                            </a>
                        </td>
                        <td>{{ $group->position }}</td>
                        <td>{{ $group->level }}</td>
                        <td>{{ $group->download_slots ?? 'Unlimited' }}</td>
                        <td>
                            <i class="{{ config('other.font-awesome') }} fa-circle" style="color: {{ $group->color }};"></i>
                            {{ $group->color }}
                        </td>
                        <td>
                            <i class="{{ $group->icon }}"></i>
                            [{{ $group->icon }}]
                        </td>
                        <td>
                            @if ($group->effect !== '' && $group->effect !== 'none')
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_internal)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_modo)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_admin)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_owner)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_trusted)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_immune)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_freeleech)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_double_upload)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->is_incognito)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->can_upload)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                        <td>
                            @if ($group->autogroup)
                                <i class="{{ config('other.font-awesome') }} fa-check text-green"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times text-red"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
