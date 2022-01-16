@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.groups.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.user') }} Groups</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Groups</h2>
        <a href="{{ route('staff.groups.create') }}" class="btn btn-primary">Add New Group</a>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.position') }}</th>
                    <th>Level</th>
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
                            <a
                                    href="{{ route('staff.groups.edit', ['group' => $group->name, 'id' => $group->id]) }}">{{ $group->name }}</a>
                        </td>
                        <td>{{ $group->position }}</td>
                        <td>{{ $group->level }}</td>
                        <td><i class="{{ config('other.font-awesome') }} fa-circle"
                               style="color: {{ $group->color }};"></i>
                            {{ $group->color }}</td>
                        <td><i class="{{ $group->icon }}"></i> [{{ $group->icon }}]</td>
                        <td>@if ($group->effect == '' || $group->effect == 'none')<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_internal == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_modo == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_admin == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_owner == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_trusted == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_immune == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_freeleech == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_double_upload == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->is_incognito == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->can_upload == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                        <td>@if ($group->autogroup == 0)<i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"></i>@else<i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"></i>@endif</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
