@extends('layout.default')

@section('title')
    <title>{{ $user->username }} {{ __('user.gifts') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('earnings.index', ['username' => $user->username]) }}" class="breadcrumb__link">
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
                <a class="panel__action" href="{{ route('gifts.create', ['username' => $user->username]) }}">
                    {{ __('bon.send-gift') }}
                </a>
            </div>
        </header>
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>{{ __('bon.sender') }}</th>
                <th>{{ __('bon.receiver') }}</th>
                <th>{{ __('bon.points') }}</th>
                <th>{{ __('bon.date') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($gifttransactions as $b)
                <tr>
                    <td>
                        <a href="{{ route('users.show', ['username' => $b->senderObj->username]) }}">
                            <span class="badge-user text-bold">{{ $b->senderObj->username }}</span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('users.show', ['username' => $b->receiverObj->username]) }}">
                            <span class="badge-user text-bold">{{ $b->receiverObj->username }}</span>
                        </a>
                    </td>
                    <td>
                        {{ $b->cost }}
                    </td>
                    <td>
                        {{ $b->date_actioned }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="some-padding text-center">
            {{ $gifttransactions->links() }}
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.your-points') }}</h2>
        <div class="panel__body">{{ $userbon }}</div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('bon.total-gifts') }}</h2>
        <dl class="key-value">
            <dt>{{ __('bon.you-have-received-gifts') }}</dt>
            <dd>{{ $gifts_received }}</dd>
            <dt>{{ __('bon.you-have-sent-gifts') }}</dt>
            <dd>{{ $gifts_sent }}</dd>
        </div>
    </section>
@endsection
