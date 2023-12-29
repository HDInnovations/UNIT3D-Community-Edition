@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.gifts') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['user' => $user]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('users.earnings.index', ['user' => $user]) }}" class="breadcrumb__link">
            {{ __('bon.bonus') }} {{ __('bon.points') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('bon.gifts') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('page', 'page__bonus--gifts')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('bon.gifts') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <a
                        class="form__button form__button--text"
                        href="{{ route('users.gifts.create', ['user' => $user]) }}"
                    >
                        {{ __('bon.send-gift') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('bon.sender') }}</th>
                        <th>{{ __('bon.receiver') }}</th>
                        <th>{{ __('bon.points') }}</th>
                        <th>{{ __('bon.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gifts as $gift)
                        <tr>
                            <td>
                                <x-user_tag :user="$gift->sender" :anon="false" />
                            </td>
                            <td>
                                <x-user_tag :user="$gift->receiver" :anon="false" />
                            </td>
                            <td>
                                {{ $gift->cost }}
                            </td>
                            <td>
                                {{ $gift->created_at }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $gifts->links('partials.pagination') }}
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $bon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.total-gifts') }}</h2>
        <dl class="key-value">
            <dt>{{ __('bon.you-have-received-gifts') }}</dt>
            <dd>{{ $receivedGifts }}</dd>
            <dt>{{ __('bon.you-have-sent-gifts') }}</dt>
            <dd>{{ $sentGifts }}</dd>
        </dl>
    </section>
@endsection
