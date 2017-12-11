@extends('layout.default')

@section('title')
	<title>Forums - Staff Dashboard - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
	<meta name="description" content="Forums - Staff Dashboard">
@stop

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Forums</span>
  </a>
</li>
@stop

@section('content')
<div class="container box">
		<h2>Forums</h2>
		<a href="{{ route('staff_forum_add') }}" class="btn btn-primary">Add new forum</a>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($categories as $c)
					<tr class="success">
						<td><a href="{{ route('staff_forum_edit', array('slug' => $c->slug, 'id' => $c->id)) }}">{{ $c->name }}</a></td>
						<td><a href="{{ route('staff_forum_delete', ['slug' => $c->slug, 'id' => $c->id]) }}" class="btn btn-danger">Delete</a></td>
					</tr>
					@foreach($c->getForumsInCategory() as $f)
						<tr>
							<td><a href="{{ route('staff_forum_edit', array('slug' => $f->slug, 'id' => $f->id)) }}">---- {{ $f->name }}</a></td>
							<td><a href="{{ route('staff_forum_delete', ['slug' => $f->slug, 'id' => $f->id]) }}" class="btn btn-danger">Delete</a></td>
						</tr>
					@endforeach
				@endforeach
			</tbody>
		</table>
</div>
@stop
