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
            <h2>{{ __('stat.top-seedtime') }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-purple"><strong><i
                                    class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('stat.top-seedtime') }}
                        </strong>
                    </p>
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('torrent.seedtime') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($seedtime as $key => $s)
                            <tr>
                                <td>
                                    {{ ++$key }}
                                </td>
                                <td @if (auth()->user()->username == $s->user->username) class="mentions" @endif>
                                    @if ($s->private_profile == 1)
                                        <span class="badge-user text-bold"><span class="text-orange"><i
                                                        class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                        aria-hidden="true"></i>{{ strtoupper(__('common.hidden')) }}</span>@if (auth()->user()->id == $b->id || auth()->user()->group->is_modo)
                                                <a href="{{ route('users.show', ['username' => $s->username]) }}">({{ $s->username }}</a></span>
                                    @endif
                                    @else
                                        <span class="badge-user text-bold"><a
                                                    href="{{ route('users.show', ['username' => $s->username]) }}">{{ $s->username }}</a></span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-purple">{{ App\Helpers\StringHelper::timeElapsed($s) }}</span>
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
