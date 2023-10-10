@extends('layout.default')

@section('title')
    <title>Subscriptions - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Subscriptions - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="#" class="breadcrumb__link">
            Donations
        </a>
    </li>
    <li class="breadcrumb--active">
        Subscriptions
    </li>
@endsection

@section('page', 'page__donations-subscriptions--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Donors</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <a href="{{ route('staff.donations.subscriptions.create') }}" class="form__button form__button--text">
                        {{ __('common.add') }} VIP
                    </a>
                </div>
            </div>
        </header>
        <div class="panel__body">
            <!-- Upcoming Users -->
            <h3>Upcoming</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('common.group') }}</th>
                            <th>VIP start</th>
                            <th>VIP end</th>
                            <th width="10%">{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions_upcoming as $subscription)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['user' => $subscription->user->username]) }}">
                                        {{ $subscription->user->username }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $subscription->user->group->color }}; background-image:{{ $subscription->user->group->effect }};">
                                        <i class="{{ $subscription->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $subscription->user->group->name }}"></i>
                                        {{ $subscription->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->end_at)) }}
                                </td>

                                <td>
                                    <a
                                        class="form__button form__button--text"
                                        href="{{ route('staff.donations.subscriptions.edit', ['id' => $subscription->id]) }}"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $subscriptions_upcoming->count())
                    <div class="margin-10">
                        {{ __('common.no-result') }}
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $subscriptions_upcoming->links() }}
                </div>
            </div>

            <!-- Active Users -->
            <h3>Active</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('common.group') }}</th>
                            <th>VIP Start</th>
                            <th>VIP End</th>
                            <th width="10%">{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions_active as $subscription)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['user' => $subscription->user->username]) }}">
                                        {{ $subscription->user->username }}
                                    </a>
                                    @if ($subscription->user->is_donor && $subscription->end_at == $curdate->toDateString())
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Demoted today!"></i>
                                    @elseif ($subscription->user->is_donor === false)
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="Shouln't this User be a VIP?"></i>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $subscription->user->group->color }}; background-image:{{ $subscription->user->group->effect }};">
                                        <i class="{{ $subscription->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $subscription->user->group->name }}"></i>
                                        {{ $subscription->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->end_at)) }}
                                </td>

                                <td>
                                    <a
                                        class="form__button form__button--text"
                                        href="{{ route('staff.donations.subscriptions.edit', ['id' => $subscription->id]) }}"
                                    >
                                        {{ __('common.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $subscriptions_active->count())
                    <div class="margin-10">
                        {{ __('common.no-result') }}
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $subscriptions_active->links() }}
                </div>
            </div>

            <!-- Old VIP User-Records -->
            <h3>Previous VIP User Records</h3>
            <div class="table-responsive">
                <table class="table table-condensed table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('common.group') }}</th>
                            <th>VIP Start</th>
                            <th>VIP End</th>
                            <th width="10%">{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions_inactive as $subscription)
                            <tr>
                                <td>
                                    <a href="{{ route('users.show', ['user' => $subscription->user->username]) }}">
                                        {{ $subscription->user->username }}
                                    </a>
                                    @if ($subscription->user->is_donor && !in_array($subscription->user_id, $subscriptions_active_arr) == true)
                                        <i class="fas fa-exclamation-circle text-orange" aria-hidden="true" data-toggle="tooltip" title="" data-original-title="User is still VIP but subscription has ended!"></i>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-user text-bold"
                                        style="color:{{ $subscription->user->group->color }}; background-image:{{ $subscription->user->group->effect }};">
                                        <i class="{{ $subscription->user->group->icon }}" data-toggle="tooltip"
                                            data-original-title="{{ $subscription->user->group->name }}"></i>
                                        {{ $subscription->user->group->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->start_at)) }}
                                </td>

                                <td>
                                    {{ date('Y-m-d', strtotime($subscription->end_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (! $subscriptions_inactive->count())
                    <div class="margin-10">
                        {{ __('common.no-result') }}
                    </div>
                @endif
                <br>
                <div class="text-center">
                    {{ $subscriptions_inactive->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
