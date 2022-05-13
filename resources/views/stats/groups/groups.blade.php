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
