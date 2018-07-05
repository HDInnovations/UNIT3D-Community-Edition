@extends('layout.default')

@section('title')
    <title>Bans - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Bans - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('getBans') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Bans</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>User Bans</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-red"><strong><i class="fa fa-ban"></i> Bans</strong></p>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Judge</th>
                            <th>Ban Reason</th>
                            <th>Unban Reason</th>
                            <th>Created</th>
                            <th>Removed</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($bans) == 0)
                            <p>The are no bans in database</p>
                        @else
                            @foreach($bans as $b)
                                <tr>
                                    <td>
                                        {{ $b->id }}
                                    </td>
                                    <td class="user-name">
                                        <a class="name"
                                           href="{{ route('profile', ['username' => $b->banneduser->username, 'id' => $b->owned_by ]) }}">{{ $b->banneduser->username }}</a>
                                    </td>
                                    <td class="user-name">
                                        <a class="name"
                                           href="{{ route('profile', ['username' => $b->staffuser->username, 'id' => $b->created_by ]) }}">{{ $b->staffuser->username }}</a>
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
