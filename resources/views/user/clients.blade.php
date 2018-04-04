@extends('layout.default')

@section('title')
<title>{{ trans('user.my-seedboxes') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
</li>
<li>
    <a href="{{ route('user_clients', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('user.my-seedboxes') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-2 col-sm-offset-1">
    <div class="well well-sm mt-0">
		<h3>{{ trans('user.add-seedbox') }}</h3>
    {{ Form::open(['route' => ['addcli', 'username' => $user->username, 'id' => $user->id], 'method' => 'post' , 'role' => 'form', 'class' => 'login-frm']) }}
			<div class="form-group input-group">
				<input type="password" name="password" class="form-control" placeholder="{{ trans('user.current-password') }}" required>
			</div>
			<div class="form-group input-group">
				<input type="text" name="ip" class="form-control" placeholder="{{ trans('user.client-ip-address') }}" required>
			</div>
			<div class="form-group input-group">
				<input type="text" name="client_name" class="form-control" placeholder="{{ trans('user.username-seedbox') }}" required>
			</div>
			<center><button type="submit" class="btn btn-primary btn-sm">{{ trans('common.submit') }}</a></center>
		{{ Form::close() }}
	</div>
</div>
<div class="col-sm-8">
    <div class="well well-sm mt-0">
      <p class="lead text-orange text-center"><i class="fa fa-exclamation-triangle"></i> <strong>{{ strtoupper(trans('user.disclaimer')) }}</strong> <i class="fa fa-exclamation-triangle"></i></p>
      <p class="lead text-orange text-center">{{ trans('user.disclaimer-info') }} &nbsp;<br><strong>{{ trans('user.disclaimer-info-bordered') }}</strong></p>
    </div>
</div>
</div>


<div class="row">
	<div class="container box">
		<h3 class="text-center">{{ trans('user.my-seedboxes') }}</h3>
	@if(count($clients) > 0)
    <div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped table-hover">
      <tr>
        <th>{{ trans('torrent.agent') }}</th>
        <th>IP</th>
        <th>{{ trans('common.added') }}</th>
        <th>{{ trans('common.remove') }}</th>
      </tr>
		@foreach($clients as $client)
		<tr>
		<td>{{ $client->name }}</td>
		<td>{{ $client->ip }}</td>
		<td>{{ $client->created_at }}</td>
		<td>
            {{ Form::open(['route' => ['rmcli', 'username' => $user->username , 'id' => $user->id], 'role' => 'form', 'class' => 'login-frm']) }}
            <input type='hidden' name="cliid" value="{{ $client->id }}">
            <input type="hidden" name="userid" value="{{ $user->id }}">
            <button type="submit" class="btn btn-danger">{{ trans('common.delete') }}</button>
            {{ Form::close() }}
        </td>
        </tr>
		@endforeach
	</table>
    </div>
		@else
		<li class="list-group-item">
			<h4 class="text-center">{{ trans('user.no-seedboxes') }}</h4>
		</li>
		@endif
	</div>
</div>
@endsection
