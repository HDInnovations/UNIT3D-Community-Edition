@extends('layout.default')

@section('title')
    <title>Invites Log - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Invites Log - {{ __('staff.staff-dashboard') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.invites.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.invites-log') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <h2>{{ __('staff.invites-log') }}</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2><span class="text-blue"><strong><i class="{{ config('other.font-awesome') }} fa-note"></i>
                        {{ $invitecount }} </strong></span>{{ __('user.invites-send') }} </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('user.sender') }}</th>
                                <th>{{ __('common.email') }}</th>
                                <th>Token</th>
                                <th>{{ __('user.created-on') }}</th>
                                <th>{{ __('user.expires-on') }}</th>
                                <th>{{ __('user.accepted-by') }}</th>
                                <th>{{ __('user.accepted-at') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($invites) == 0)
                                <p>The are no invite logs in the database for this user!</p>
                            @else
                                @foreach ($invites as $invite)
                                    <tr>
                                        <td>
                                            <a href="{{ route('users.show', ['username' => $invite->sender->username]) }}">
                                                    <span class="text-bold"
                                                          style="color:{{ $invite->sender->group->color }}; ">
                                                        <i class="{{ $invite->sender->group->icon }}"></i>
                                                        {{ $invite->sender->username }}
                                                    </span>
                                            </a>
                                        </td>
                                        <td>
                                            {{ $invite->email }}
                                        </td>
                                        <td>
                                            {{ $invite->code }}
                                        </td>
                                        <td>
                                            {{ $invite->created_at }}
                                        </td>
                                        <td>
                                            {{ $invite->expires_on }}
                                        </td>
                                        <td>
                                            @if ($invite->accepted_by != null && $invite->accepted_by != 1)
                                                <a href="{{ route('users.show', ['username' => $invite->receiver->username]) }}">
                                                        <span class="text-bold"
                                                              style="color:{{ $invite->receiver->group->color }}; ">
                                                            <i class="{{ $invite->receiver->group->icon }}"></i>
                                                            {{ $invite->receiver->username }}
                                                        </span>
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($invite->accepted_at != null)
                                                {{ $invite->accepted_at }}
                                            @else
                                                N/A
                                            @endif
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
                {{ $invites->links() }}
            </div>
        </div>
    </div>
@endsection
