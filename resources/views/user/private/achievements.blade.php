@extends('layout.default')

@section('title')
    <title>{{ $user->username }} @lang('user.achievements') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('profile', ['username' => $user->slug, 'id' => $user->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('achievements') }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }} @lang('user.achievements')</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="container-fluid">
        <div class="block">
            @include('user.buttons.achievement')
            <div class="header gradient blue">
                <div class="inner_content">
                    <h1>
                        {{ $user->username }} @lang('user.achievements')
                    </h1>
                </div>
            </div>
<div class="some-padding">
            <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('user.unlocked-achievements')</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.description')</th>
                                    <th>@lang('common.progress')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($achievements as $item)
                                    <tr>
                                        <td><img src="/img/badges/{{ $item->details->name }}.png"
                                                 data-toggle="tooltip"
                                                 data-original-title="{{ $item->details->name }}"></td>
                                        <td>{{ $item->details->description }}</td>
                                        @if ($item->isUnlocked())
                                            <td><span class="label label-success">@lang('user.unlocked')</span></td> @else
                                            <td><span class="label label-warning">@lang('common.progress'):
                                                    {{$item->points}}/{{$item->details->points}}</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-center">
                <div class="text-green well well-sm">
                    <?php $unlocked = auth()->user()->unlockedAchievements()->count() ?>
                    <h3>
                        <strong>@lang('user.unlocked-achievements'):</strong>{{
					$unlocked }}
                    </h3>
                </div>
                <div class="text-red well well-sm">
                    <?php $lock = auth()->user()->lockedAchievements()->count() ?>
                    <h3>
                        <strong>@lang('user.locked-achievements'):</strong>{{ $lock
					}}
                    </h3>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('user.pending-achievements')</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.description')</th>
                                    <th>@lang('common.progress')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pending as $p)
                                    <tr>
                                        <td><img src="/img/badges/{{ $p->details->name }}.png"
                                                 data-toggle="tooltip"
                                                 data-original-title="{{ $p->details->name }}"></td>
                                        <td>{{ $p->details->description }}</td>
                                        <td><span class="label label-warning">@lang('common.progress'):
                                                {{$p->points}}/{{$p->details->points}}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('user.locked-achievements')</div>
                    <div class="panel-body">
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.description')</th>
                                    <th>@lang('common.progress')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($locked as $l)
                                    <tr>
                                        <td><img src="/img/badges/{{ $l->details->name }}.png"
                                                 data-toggle="tooltip"
                                                 data-original-title="{{ $l->details->name }}"></td>
                                        <td>{{ $l->details->description }}</td>
                                        <td><span class="label label-danger">@lang('user.locked')</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
@endsection
