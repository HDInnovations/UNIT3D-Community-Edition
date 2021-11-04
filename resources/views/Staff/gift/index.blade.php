@extends('layout.default')

@section('title')
    <title>Gifting - @lang('staff.staff-dashboard') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gifting - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('staff.staff-dashboard')
            </span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.gifts.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('staff.user-gifting')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('bon.gifts')</h2>
        <form action="{{ route('staff.gifts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">@lang('common.username')</label>
                <input id="username" name="username" class="form-control" placeholder="@lang('common.username')" required>
            </div>

            <div class="form-group">
                <label for="seedbonus">@lang('bon.bon')</label>
                <input type="number" class="form-control" id="seedbonus" name="seedbonus" value="0">
            </div>

            <div class="form-group">
                <label for="invites">@lang('user.invites')</label>
                <input type="number" class="form-control" id="invites" name="invites" value="0">
            </div>

            <div class="form-group">
                <label for="fl_tokens">@lang('torrent.freeleech-token')</label>
                <input type="number" class="form-control" id="fl_tokens" name="fl_tokens" value="0">
            </div>

            <button type="submit" class="btn btn-default">@lang('bon.send-gift')</button>
        </form>
    </div>
@endsection
