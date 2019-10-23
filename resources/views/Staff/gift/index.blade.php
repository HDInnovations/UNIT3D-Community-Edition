@extends('layout.default')

@section('title')
    <title>Gifting - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gifting - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('systemGift') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Gifting</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Gifts</h2>
        <form action="{{ route('sendSystemGift') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="users">@lang('common.username')</label>
                <label>
                    <input name="username" class="form-control" placeholder="@lang('common.username')" required>
                </label>
            </div>

            <div class="form-group">
                <label for="name">BON</label>
                <label>
                    <input type="number" class="form-control" name="seedbonus" value="0">
                </label>
            </div>

            <div class="form-group">
                <label for="name">Invites</label>
                <label>
                    <input type="number" class="form-control" name="invites" value="0">
                </label>
            </div>

            <div class="form-group">
                <label for="name">FL Tokens</label>
                <label>
                    <input type="number" class="form-control" name="fl_tokens" value="0">
                </label>
            </div>

            <button type="submit" class="btn btn-default">Send</button>
        </form>
    </div>
@endsection
