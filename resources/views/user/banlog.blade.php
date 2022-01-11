@extends('layout.default')

@section('title')
    <title>Ban Log - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('user.ban-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>
                <a href="{{ route('users.show', ['username' => $user->username]) }}">
                    {{ $user->username }}
                </a>
                {{ __('user.ban-log') }}
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>{{ __('user.bans') }} </strong>
                        </span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('common.user') }}</th>
                                <th>{{ __('user.judge') }}</th>
                                <th>{{ __('user.reason-ban') }}</th>
                                <th>{{ __('user.reason-unban') }}</th>
                                <th>{{ __('user.created') }}</th>
                                <th>{{ __('user.removed') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($bans) == 0)
                                <tr>
                                    <td>
                                        <p>{{ __('user.no-ban') }}</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($bans as $ban)
                                    <tr>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('users.show', ['username' => $ban->banneduser->username]) }}">{{ $ban->banneduser->username }}</a>
                                        </td>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('users.show', ['username' => $ban->staffuser->username]) }}">{{ $ban->staffuser->username }}</a>
                                        </td>
                                        <td>
                                            {{ $ban->ban_reason }}
                                        </td>
                                        <td>
                                            {{ $ban->unban_reason }}
                                        </td>
                                        <td>
                                            {{ $ban->created_at }}
                                        </td>
                                        <td>
                                            {{ $ban->removed_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
