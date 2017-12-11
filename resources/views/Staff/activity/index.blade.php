@extends('layout.default')

@section('title')
	<title>Activity Log - Staff Dashboard - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
	<meta name="description" content="Activity Log - Staff Dashboard">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('activityLog') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Activity Log</span>
  </a>
</li>
@stop

@section('content')
<div class="container-fluid">
<div class="block">
  <h2>Activity Log</h2>
  <hr>
    <p class="text-red"><strong><i class="fa fa-list"></i> Activity Log</strong></p>
		<div class="table-responsive">
      <table class="table table-condensed table-striped table-bordered">
  <thead>
    <tr>
			<th>No</th>
			<th>Subject</th>
			<th>URL</th>
			<th>Method</th>
			<th>Ip</th>
			<th width="300px">User Agent</th>
			<th>User Id</th>
			<th>Action</th>
		</tr>
  </thead>
  <tbody>
    @if($logs->count())
			@foreach($logs as $key => $log)
			<tr>
				<td>{{ ++$key }}</td>
				<td>{{ $log->subject }}</td>
				<td class="text-success">{{ $log->url }}</td>
				<td><label class="label label-info">{{ $log->method }}</label></td>
				<td class="text-danger">{{ $log->ip }}</td>
				<td class="text-warning">{{ $log->agent }}</td>
				<td>{{ $log->user_id }}</td>
				<td><button class="btn btn-danger btn-sm">Delete</button></td>
			</tr>
			@endforeach
		@endif
  </tbody>
</table>
{{ $logs->links() }}
</div>
</div>
</div>
@stop
