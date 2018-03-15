@extends('layout.default')

@section('title')
<title>My Seedboxs - {{ Config::get('other.title') }}</title>
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('profil', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $user->username }}</span>
    </a>
</li>
<li>
    <a href="{{ route('user_clients', ['username' => $user->username, 'id' => $user->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">My Seedboxs</span>
    </a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col-sm-2 col-sm-offset-1">
    <div class="well well-sm mt-0">
		<h3>Add Seedbox</h3>
    {{ Form::open(['route' => ['addcli', 'username' => $user->username, 'id' => $user->id], 'method' => 'post' , 'role' => 'form', 'class' => 'login-frm']) }}
			<div class="form-group input-group">
				<input type="password" name="password" class="form-control" placeholder="Current Password" required>
			</div>
			<div class="form-group input-group">
				<input type="text" name="ip" class="form-control" placeholder="Client IP Address" required>
			</div>
			<div class="form-group input-group">
				<input type="text" name="client_name" class="form-control" placeholder="UsernameSeebox1" required>
			</div>
			<center><button type="submit" class="btn btn-primary btn-sm">{{ trans('common.submit') }}</a></center>
		{{ Form::close() }}
	</div>
</div>
<div class="col-sm-8">
    <div class="well well-sm mt-0">
      <p class="lead text-orange text-center"><i class="fa fa-exclamation-triangle"></i> <strong>DISCLAIMER</strong> <i class="fa fa-exclamation-triangle"></i></p>
      <p class="lead text-orange text-center">We by default do not log users IP addresses like most trackers. By adding your seedbox IP below it is expected that you know your IP's listed below are now stored in our database unless you delete the records. &nbsp;<br><strong>Seedbox IP's added will then trigger high speed torrent tag on torrents seeded from IP's listed below</strong></p>
    </div>
</div>
</div>


<div class="row">
	<div class="container box">
		<h3 class="text-center">My High Speed Seedboxes</h3>
	@if(count($clients) > 0)
    <div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped table-hover">
      <tr>
        <th>Client</th>
        <th>IP</th>
        <th>Added</th>
        <th>Remove</th>
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
			<h4 class="text-center">No Seedboxes :(</h4>
		</li>
		@endif
	</div>
</div>
@endsection
