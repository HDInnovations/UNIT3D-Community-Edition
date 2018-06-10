@extends('layout.default')

@section('title')
    <title>Gifting - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Gifting - Staff Dashboard">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
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
            {{ csrf_field() }}
            <div class="form-group">
                <label for="users">Select a User</label>
                <select class="form-control user-select-placeholder-single" name="username">
                    @foreach($users as $user)
                        <option value="{{ $user->username }}">{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">BON</label>
                <input type="number" class="form-control" name="seedbonus" value="0">
            </div>

            <div class="form-group">
                <label for="name">Invites</label>
                <input type="number" class="form-control" name="invites" value="0">
            </div>

            <div class="form-group">
                <label for="name">FL Tokens</label>
                <input type="number" class="form-control" name="fl_tokens" value="0">
            </div>

            <button type="submit" class="btn btn-default">Send</button>
        </form>
    </div>
@endsection

@section('javascripts')
    <script type="text/javascript">
      $('.user-select-placeholder-single').select2({
        placeholder: "Select A User",
        allowClear: true
      });
    </script>
@endsection
