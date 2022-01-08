@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('stats') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.stats') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('groups') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('stat.groups') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        @include('partials.statsgroupmenu')
        <div class="block">
            <h2>{{ __('stat.groups') }}</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-users"></i>
                    {{ __('stat.groups') }}</strong>
                ({{ __('stat.users-per-group') }})</p>
            <div class="row col-md-offset-2">
                @foreach ($groups as $group)
                    <div class="well col-md-3" style="margin: 10px;">
                        <div class="text-center">
                            <a href="{{ route('group', ['id' => $group->id]) }}">
                                <h2 style="color:{{ $group->color }};">
                                    <i class="{{ $group->icon }}" aria-hidden="true"></i>&nbsp;{{ $group->name }}</h2>
                            </a>
                            <p class="lead text-blue">{{ $group
                                        ->users()
                                        ->withTrashed()
                                        ->count() }} Users</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
