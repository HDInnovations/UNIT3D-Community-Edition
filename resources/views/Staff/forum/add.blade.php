@extends('layout.default')

@section('title')
	<title>Add Forums - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="Add Forums - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_forum_add') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Add Forums</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
	<h2>Add a new Forum</h2>

		{{ Form::open(array('route' => 'staff_forum_add')) }}
			<div class="form-group">
				<label for="forum_type">Forum Type</label>
				<select name="forum_type" class="form-control">
					<option value="category">Category</option>
					<option value="forum">Forum</option>
				</select>
			</div>

			<div class="form-group">
				<label for="title">Title</label>
				<input type="text" name="title" class="form-control">
			</div>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" cols="30" rows="10"></textarea>
			</div>

			<div class="form-group">
				<label for="parent_id">Parent forum</label>
				<select name="parent_id" class="form-control">
					@foreach($categories as $c)
						<option value="{{ $c->id }}">{{ $c->name }}</option>
						{{-- @foreach($c->getForumsInCategory() as $f)
							<option value="{{ $f->id }}">---- {{ $f->name }}</option>
						@endforeach --}}
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="position">Position</label>
				<input type="text" name="position" class="form-control" placeholder="The position number">
			</div>

			<h3>Permissions</h3>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Groups</th>
						<th>View the forum</th>
						<th>Read topics</th>
						<th>Start new topic</th>
						<th>Reply to topics</th>
					</tr>
				</thead>
				<tbody>
					@foreach($groups as $g)
						<tr>
							<td>{{ $g->name }}</td>
							<td><input type="checkbox" name="permissions[{{ $g->id }}][show_forum]" value="1"></td>
							<td><input type="checkbox" name="permissions[{{ $g->id }}][read_topic]" value="1"></td>
							<td><input type="checkbox" name="permissions[{{ $g->id }}][start_topic]" value="1"></td>
							<td><input type="checkbox" name="permissions[{{ $g->id }}][reply_topic]" value="1"></td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<button type="submit" class="btn btn-default">Save Forum</button>
		{{ Form::close() }}
</div>
@endsection
