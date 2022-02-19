@extends('layout.default')

@section('title')
    <title>Bans - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Bans - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.bans.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.bans-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>{{ __('common.user') }} {{ __('user.bans') }}</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-ban"></i>
                            {{ __('user.bans') }}
                        </strong></p>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
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
                                <p>The are no bans in database</p>
                            @else
                                @foreach ($bans as $b)
                                    <tr>
                                        <td>
                                            {{ $b->id }}
                                        </td>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('users.show', ['username' => $b->banneduser->username]) }}">{{ $b->banneduser->username }}</a>
                                        </td>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('users.show', ['username' => $b->staffuser->username]) }}">{{ $b->staffuser->username }}</a>
                                        </td>
                                        <td>
                                            {{ $b->ban_reason }}
                                        </td>
                                        <td>
                                            {{ $b->unban_reason }}
                                        </td>
                                        <td>
                                            {{ $b->created_at }}
                                        </td>
                                        <td>
                                            {{ $b->removed_at }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $bans->links() }}
            </div>
        </div>
    </div>
@endsection
