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
    <li class="breadcrumb--active">
        {{ __('stat.groups') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.statsgroupmenu')
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('stat.groups') }}</h2>
            <hr>
            <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-users"></i>
                    {{ __('stat.groups') }}</strong>
                ({{ __('stat.users-per-group') }})</p>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>{{ __('common.group') }}</th>
                            <th class="text-right">{{ __('common.users') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($groups as $group)
                            <tr>
                                <td>
                                    <a href="{{ route('group', ['id' => $group->id]) }}">
                                        <span style="color:{{ $group->color }};">
                                            <i class="{{ $group->icon }}" aria-hidden="true"></i>
                                            {{ $group->name }}
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <p class="text-blue text-right">
                                        {{ $group->users()->withTrashed()->count() }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
