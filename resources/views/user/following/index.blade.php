@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.following') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.following') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    @if (auth()->id() === $user->id || auth()->user()->group->is_modo)
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.following') }}</h2>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('user.avatar') }}</th>
                            <th>{{ __('user.user') }}</th>
                            <th>{{ __('common.created_at') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($followings as $following)
                            <tr>
                                <td>
                                    <img
                                        src="{{ url($following->image === null ? 'img/profile.png' : 'files/img/' . $following->image) }}"
                                        alt="{{ $following->username }}"
                                        class="user-search__avatar"
                                    />
                                </td>
                                <td>
                                    <x-user_tag :anon="false" :user="$following" />
                                </td>
                                <td>{{ $following->follow->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Not following</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $followings->links('partials.pagination') }}
        </section>
    @else
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.private-profile') }}</h2>
            <div class="panel__body">{{ __('user.not-authorized') }}</div>
        </section>
    @endif
@endsection
