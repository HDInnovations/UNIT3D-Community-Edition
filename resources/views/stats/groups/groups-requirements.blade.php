@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('groups') }}" class="breadcrumb__link">
            {{ __('stat.groups') }}
        </a>
    </li>
    <li class="breadcrumb--active">{{ __('common.groups') }} Requirements</li>
@endsection

@section('nav-tabs')
    @include('partials.statsgroupmenu')
@endsection

@section('page', 'page__stats--groups')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('stat.groups') }}</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('common.group') }}</th>
                        <th>Requirement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td style="min-width: 20%">
                                <a
                                    href="{{ route('group', ['id' => $group->id]) }}"
                                    style="color: {{ $group->color }}"
                                >
                                    <i class="{{ $group->icon }}"></i>
                                    {{ $group->name }}
                                </a>
                            </td>
                            <td>
                                @if ($group->autogroup)
                                    <table class="data-table requirements-table">
                                        <thead>
                                            <tr>
                                                <td></td>
                                                <td>Requirement</td>
                                                <td>Actual</td>
                                                <td>Passed?</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Min. Upload</td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::formatBytes($group->min_uploaded ?? 0) }}
                                                </td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::formatBytes($user->uploaded ?? 0) }}
                                                </td>
                                                <td>
                                                    @if ($user->uploaded >= $group->min_uploaded ?? 0)
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                                        ></i>
                                                    @else
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-x text-red"
                                                        ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Min. Ratio</td>
                                                <td>{{ $group->min_ratio ?? 0 }}</td>
                                                <td>
                                                    {{ $user->ratio ?? 0 }}
                                                </td>
                                                <td>
                                                    @if ($user->ratio >= $group->min_ratio ?? 0)
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                                        ></i>
                                                    @else
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-x text-red"
                                                        ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Min. Account Age</td>
                                                <td>
                                                    @if ($group->min_age > 0)
                                                        {{ \App\Helpers\StringHelper::timeElapsed($group->min_age ?? 0) }}
                                                    @else
                                                        {{ $group->min_age ?? 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::timeElapsed($user_account_age) }}
                                                </td>
                                                <td>
                                                    @if ($user->created_at->addRealSeconds($group->min_age ?? 0)->isBefore($current))
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                                        ></i>
                                                    @else
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-x text-red"
                                                        ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Min. Average Seedtime</td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::timeElapsed($group->min_avg_seedtime ?? 0) }}
                                                </td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::timeElapsed($user_avg_seedtime ?? 0) }}
                                                </td>
                                                <td>
                                                    @if ($group->min_avg_seedtime <= $user_avg_seedtime)
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                                        ></i>
                                                    @else
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-x text-red"
                                                        ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Min. Seedsize</td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::formatBytes($group->min_seedsize ?? 0) }}
                                                </td>
                                                <td>
                                                    {{ \App\Helpers\StringHelper::formatBytes($user_seed_size ?? 0) }}
                                                </td>
                                                <td>
                                                    @if ($group->min_seedsize <= $user_seed_size)
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-check text-green"
                                                        ></i>
                                                    @else
                                                        <i
                                                            class="{{ config('other.font-awesome') }} fa-x text-red"
                                                        ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
