@extends('layout.default')

@section('title')
    <title>{{ $user->username }} - {{ __('user.invites') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('users.show', ['username' => $user->username]) }}" class="breadcrumb__link">
            {{ $user->username }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('user.invites') }}
    </li>
@endsection

@section('nav-tabs')
    @include('user.buttons.user')
@endsection

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('user.invites') }}</h2>
            <div class="panel__actions">
                <form class="panel__action" action="{{ route('invites.create') }}">
                    <button class="form__button form__button--text">
                        {{ __('user.send-invite') }}
                    </button>
                </form>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('user.sender') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('user.code') }}</th>
                        <th>{{ __('user.created-on') }}</th>
                        <th>{{ __('user.expires-on') }}</th>
                        <th>{{ __('user.accepted-by') }}</th>
                        <th>{{ __('user.accepted-at') }}</th>
                        <th>{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invites as $invite)
                        <tr>
                            <td>
                                <x-user_tag :user="$invite->sender" :anon="false" />
                            </td>
                            <td>{{ $invite->email }}</td>
                            <td>{{ $invite->code }}</td>
                            <td>{{ $invite->created_at }}</td>
                            <td>{{ $invite->expires_on }}</td>
                            <td>
                                @if ($invite->accepted_by !== null && $invite->accepted_by !== 1)
                                    <x-user_tag :user="$invite->receiver" :anon="false" />
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $invite->accepted_at ?? 'N/A' }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('invites.send', ['id' => $invite->id]) }}"
                                            method="POST"
                                            x-data
                                        >
                                            @csrf
                                            <button 
                                                x-on:click.prevent="Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'Are you sure you want to resend the email to: {{ $invite->email }}?',
                                                    icon: 'warning',
                                                    showConfirmButton: true,
                                                    showCancelButton: true,
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $root.submit();
                                                    }
                                                })"
                                                class="form__button form__button--text"
                                                @disabled($invite->accepted_at !== null)
                                            >
                                                {{ __('common.resend') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No Invitees</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $invites->links('partials.pagination') }}
    </section>
@endsection
