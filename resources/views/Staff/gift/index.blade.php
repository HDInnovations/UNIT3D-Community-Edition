@extends('layout.default')

@section('title')
	<title>Gifting - Staff Dashboard - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
	<meta name="description" content="Gifting - Staff Dashboard">
@stop

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
@stop

@section('content')
<div class="container box">
        <h2>Gifts</h2>
        {{ Form::open(['route' => 'sendSystemGift' , 'method' => 'post' , 'role' => 'form' , 'class' => 'form-horizontal']) }}
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
                <input type="number" class="form-control" name="bonus_points" value="0">
            </div>

            <div class="form-group">
                <label for="name">Invites</label>
                <input type="number" class="form-control" name="invites" value="0">
            </div>

            <button type="submit" class="btn btn-default">Send</button>
        {{ Form::close() }}
</div>
@stop

@section('javascripts')
    <script type="text/javascript">
        $('.user-select-placeholder-single').select2({
            placeholder: "Select A User",
            allowClear: true
        });
    </script>
@endsection
