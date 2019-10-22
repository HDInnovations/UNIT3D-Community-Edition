@extends('layout.default')

@section('title')
    <title>Possible Leech Cheaters - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Possible Leech Cheaters - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('leechCheaters') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Possible Leech Cheaters</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <h2>Possible Cheaters (Ghost Leechers)</h2>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-red"><strong><i class="{{ config('other.font-awesome') }} fa-question"></i> Possible Leech Cheaters</strong></p>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Group</th>
                                <th>Joined</th>
                                <th>Last Login</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($cheaters as $cheater)
                                    <tr>
                                        <td>
                                            <a class="text-bold" href="{{ route('users.show', ['username' => $cheater->user->username]) }}">{{ $cheater->user->username }}</a>
                                        </td>
                                        <td>
                                            <span class="badge-user text-bold" style="color:{{ $cheater->user->group->color }}; background-image:{{ $cheater->user->group->effect }};">
                                                <i class="{{ $cheater->user->group->icon }}" data-toggle="tooltip" data-original-title="{{ $cheater->user->group->name }}"></i>
                                                {{ $cheater->user->group->name }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $cheater->user->created_at->toDayDateTimeString() }}
                                        </td>
                                        <td>
                                            @if ($cheater->user->last_login != null)
                                                {{ $cheater->user->last_login->toDayDateTimeString() }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                {{ $cheaters->links() }}
            </div>
        </div>
    </div>
@endsection
