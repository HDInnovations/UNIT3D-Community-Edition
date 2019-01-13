@extends('layout.default')

@section('title')
    <title>Ban Log - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="#" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Ban Log</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>
                <a href="{{ route('profile', ['username' =>  $user->username, 'id' => $user->id]) }}">
                    {{ $user->username }}
                </a>
                Ban Log
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <h2>
                        <span class="text-red">
                            <strong>Bans </strong>
                        </span>
                    </h2>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Judge</th>
                                <th>Ban Reason</th>
                                <th>Unban Reason</th>
                                <th>Created</th>
                                <th>Removed</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($bans) == 0)
                                <tr>
                                    <td>
                                        <p>The are no bans in the database for this user!</p>
                                    </td>
                                </tr>
                            @else
                                @foreach ($bans as $ban)
                                    <tr>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('profile', ['username' => $ban->banneduser->username, 'id' => $ban->owned_by ]) }}">{{ $ban->banneduser->username }}</a>
                                        </td>
                                        <td class="user-name">
                                            <a class="name"
                                               href="{{ route('profile', ['username' => $ban->staffuser->username, 'id' => $ban->created_by ]) }}">{{ $ban->staffuser->username }}</a>
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
