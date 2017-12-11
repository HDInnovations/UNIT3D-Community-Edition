@extends('layout.default')

@section('title')
<title>My Seedboxs - {{ Config::get('other.title') }}</title>
@stop

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
@stop

@section('content')
<div class="row">
  <div class="col-sm-2 col-sm-offset-1">
    <div class="well well-sm mt-0">
		<h3>Add Seedbox</h3>
    {{ Form::open(['route' => ['addcli', 'username' => $user->username, 'id' => $user->id], 'method' => 'post' , 'role' => 'form', 'class' => 'login-frm']) }}
			<div class="form-group input-group">
				<input type="password" name="password" class="form-control" placeholder="Current Password">
			</div>
			<div class="form-group input-group">
				<input type="text" name="ip" class="form-control" placeholder="Client IP Address">
			</div>
			<div class="form-group input-group">
				<input type="text" name="client_name" class="form-control" placeholder="UsernameSeebox1">
			</div>
			<center><button type="submit" class="btn btn-primary btn-sm">Save</a></center>
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
    <table class="table table-bordered table-striped">
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
		<td>{{ Form::open(['route' => ['rmcli', 'username' => $user->username , 'id' => $user->id], 'role' => 'form', 'class' => 'login-frm']) }}<input type='hidden' name="cliid" value="{{ $client->id }}"><input type="hidden" name="userid" value="{{ $user->id }}"><button type="submit" class="btn btn-danger">Delete</button>{{ Form::close() }}</td>
        </tr>
		@endforeach
	</table>
		@else
		<li class="list-group-item">
			<h4 class="text-center">No Seedboxes :(</h4>
		</li>
		@endif
	</div>
</div>

<!-- Remove Client Modal
<div class="modal fade" id="removeCModal" tabindex="-1" role="dialog" aria-labelledby="removeCModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="removeCModalLabel">Remove Seedbox</h4>
			</div>
			<div class="modal-body">
				<center><p class="text-danger">Yeah...are you sure you want to do this? Removing this seedbox will result in all torrents seeding from this client to no longer be registered as a high speed torrent!</p></center>
				{{ Form::open(array('url' => 'members/{username}.{id}/rmcli','role' => 'form', 'class' => 'login-frm')) }}
					<input type="hidden" name="cliid" value="">
					<div class="form-group input-group input-group-lg">
						<input class="form-control"  name="password" id="rmcpw" type="password" placeholder="Password">
					</div>
					<center>
						<button type="button" class="btn btn-warning" data-dismiss="modal">My bad, wrong button</button>
						<button type="submit" class="btn btn-danger">Submit</button>
					</center>
			{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
 /Remove Client Modal -->
@stop
