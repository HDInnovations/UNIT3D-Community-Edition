@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.achievements') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('users.show', ['username' => $user->username]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('achievements.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}
                {{ __('user.achievements') }}</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.achievement')
            <div class="some-padding">
                <div class="row">
                    <div class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('user.unlocked-achievements') }}</div>
                            <div class="panel-body">
                                <br/>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>{{ __('common.name') }}</th>
                                            <th>{{ __('common.description') }}</th>
                                            <th>{{ __('common.progress') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($achievements as $item)
                                            <tr>
                                                <td><img src="/img/badges/{{ $item->details->name }}.png"
                                                         alt="{{ $item->details->name }}" data-toggle="tooltip"
                                                         data-original-title="{{ $item->details->name }}"></td>
                                                <td>{{ $item->details->description }}</td>
                                                @if ($item->isUnlocked())
                                                    <td><span class="label label-success">{{ __('user.unlocked') }}</span>
                                                    </td> @else
                                                    <td><span class="label label-warning">{{ __('common.progress') }}:
                                                                {{ $item->points }}/{{ $item->details->points }}</span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ __('user.pending-achievements') }}</div>
                            <div class="panel-body">
                                <br/>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>{{ __('common.name') }}</th>
                                            <th>{{ __('common.description') }}</th>
                                            <th>{{ __('common.progress') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($pending as $p)
                                            <tr>
                                                <td><img src="/img/badges/{{ $p->details->name }}.png"
                                                         alt="{{ $p->details->name }}" data-toggle="tooltip"
                                                         data-original-title="{{ $p->details->name }}"></td>
                                                <td>{{ $p->details->description }}</td>
                                                <td><span class="label label-warning">{{ __('common.progress') }}:
                                                            {{ $p->points }}/{{ $p->details->points }}</span>
                                                    <span class="label label-danger">{{ __('user.locked') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2 text-center">
                        <div class="text-green well well-sm">
                            <h3>
                                <strong>{{ __('user.unlocked-achievements') }}
                                    :</strong>{{ auth()
                                            ->user()
                                            ->unlockedAchievements()
                                            ->count() }}
                            </h3>
                        </div>
                        <div class="text-red well well-sm">
                            <h3>
                                <strong>{{ __('user.locked-achievements') }}
                                    :</strong>{{ auth()
                                            ->user()
                                            ->lockedAchievements()
                                            ->count() }}
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
