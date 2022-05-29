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
        {{ __('common.users') }}
    </li>
@endsection

@section('nav-tabs')
    @include('partials.statsusermenu')
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('stat.top-bankers') }} ({{ __('bon.bon') }})</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-purple"><strong><i
                                    class="{{ config('other.font-awesome') }} fa-coins"></i> {{ __('stat.top-bankers') }}
                        </strong>
                        ({{ __('bon.bon') }})</p>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('common.balance') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bankers as $key => $b)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td @if (auth()->user()->username == $b->username) class="mentions" @endif>
                                    @if ($b->private_profile == 1)
                                        <span class="badge-user text-bold"><span class="text-orange"><i
                                                        class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                        aria-hidden="true"></i>{{ strtoupper(__('common.hidden')) }}</span>@if (auth()->user()->id == $b->id || auth()->user()->group->is_modo)
                                                <a href="{{ route('users.show', ['username' => $b->username]) }}">({{ $b->username }}</a></span>
                                    @endif
                                    @else
                                        <span class="badge-user text-bold"><a
                                                    href="{{ route('users.show', ['username' => $b->username]) }}">{{ $b->username }}</a></span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-purple">{{ $b->seedbonus }}</span>
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
