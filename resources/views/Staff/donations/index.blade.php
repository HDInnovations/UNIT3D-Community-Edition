@extends('layout.default')

@section('title')
    <title>@lang('staff.vips') - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('staff.vips') - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        <a href="#" class="breadcrumb__link">
            Donation
        </a>
    </li>
    <li class="breadcrumb--active">
        Subscriptions
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">VIP Users</h2>
            <div class="panel__actions">
                <a href="{{ route('staff.donations.create') }}" class="form__button form__button--text">
                    @lang('common.add') VIP
                </a>
            </div>
        </header>
        <div class="panel__body">
            <!-- Upcoming Users -->
            <h3>Upcoming</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('common.user')</th>
                            <th>@lang('common.group')</th>
                            <th>VIP start</th>
                            <th>VIP end</th>
                            <th width="10%">@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vips_upcoming as $vip)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['user' => $vip->user->username]) }}">
                                        {{ $vip->user->username }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $vip->user->group->color }}; background-image:{{ $vip->user->group->effect }};">
                                        <i class="{{ $vip->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $vip->user->group->name }}"></i>
                                        {{ $vip->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->end_at)) }}
                                </td>

                                <td>
                                    <a
                                        class="form__button form__button--text"
                                        href="{{ route('staff.donations.edit', ['id' => $vip->id]) }}"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $vips_upcoming->count())
                    <div class="margin-10">
                        @lang('common.no-result')
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $vips_upcoming->links() }}
                </div>
            </div>

            <!-- Active Users -->
            <h3>Active</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('common.user')</th>
                            <th>@lang('common.group')</th>
                            <th>VIP Start</th>
                            <th>VIP End</th>
                            <th width="10%">@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vips_active as $vip)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['username' => $vip->user->username]) }}">
                                        {{ $vip->user->username }}
                                    </a>
                                    @if ($vip->user->is_donor && $vip->end_at == $curdate->toDateString())
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Demoted today!"></i>
                                    @elseif ($vip->user->is_donor === false)
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Shouln't this User be a VIP?"></i>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $vip->user->group->color }}; background-image:{{ $vip->user->group->effect }};">
                                        <i class="{{ $vip->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $vip->user->group->name }}"></i>
                                        {{ $vip->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->end_at)) }}
                                </td>

                                <td>
                                    <a
                                        class="form__button form__button--text"
                                        href="{{ route('staff.donations.edit', ['id' => $vip->id]) }}"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $vips_active->count())
                    <div class="margin-10">
                        @lang('common.no-result')
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $vips_active->links() }}
                </div>
            </div>

            <!-- Old VIP User-Records -->
            <h3>Preivous VIP User Records</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('common.user')</th>
                            <th>@lang('common.group')</th>
                            <th>VIP Start</th>
                            <th>VIP End</th>
                            <th width="10%">@lang('common.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vips_inactive as $vip)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['username' => $vip->user->username]) }}">
                                        {{ $vip->user->username }}
                                    </a>
                                    @if ($vip->user->is_donor && !in_array($vip->user_id, $vips_active_arr) == true)
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="User is still VIP but subscription has ended!"></i>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $vip->user->group->color }}; background-image:{{ $vip->user->group->effect }};">
                                        <i class="{{ $vip->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $vip->user->group->name }}"></i>
                                        {{ $vip->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($vip->end_at)) }}

                                <td>
                                    @if ($vip->user->is_donor && !in_array($vip->user_id, $vips_active_arr) == true)
                                        <a
                                            class="form__button form__button--text"
                                            href="{{ route('user_setting', ['username' => $vip->user->username, 'id' => $vip->user->id]) }}"
                                        >
                                            Demote
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $vips_inactive->count())
                    <div class="margin-10">
                        @lang('common.no-result')
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $vips_inactive->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
