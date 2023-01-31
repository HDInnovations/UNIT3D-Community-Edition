@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.followers') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.followers') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('content')
    @if (auth()->user()->isAllowed($user,'follower','show_follower'))
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.followers') }}</h2>
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
                        @forelse ($followers as $follower)
                            <tr>
                                <td>
                                    <img
                                        src="{{ url($follower->image === null ? 'img/profile.png' : 'files/img/' . $follower->image) }}"
                                        alt="{{ $follower->username }}"
                                        class="user-search__avatar"
                                    >
                                </td>
                                <td>
                                    <x-user_tag :anon="false" :user="$follower" />
                                </td>
                                <td>{{ $follower->follow->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No Followers</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $followers->links('partials.pagination') }}
        </section>
    @else
        <section class="panelV2">
            <h2 class="panel__heading">{{ __('user.private-profile') }}</h2>
            <div class="panel__body">{{ __('user.not-authorized') }}</div>
        </section>
    @endif
@endsection
