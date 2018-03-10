@extends('layout.default')

@section('content')
<div class="container box">
		<table class="table table-striped">
			<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Role</th>
					</tr>
				</thead>
				<tbody>
					@foreach($user as $k)
						<tr>
							<td>{{ $k->id }}</td>
							<td>{{ $k->username }}</td>
							<td>{{ $k->group_id }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<hr>
	{{ Form::open(array('route' => 'staff_forum_perm')) }}
			<p>Select name of user you want to modify :</p>
			<select name="name" class="form-control">
		@foreach($user as $k)
			  <option name="name">{{ $k->username }}</option>
		@endforeach
			</select>
			<hr>
			<div class="col-md-3">
				<p>Permissions :</p>
				<code>
					1 => Validating
				</code> <br>
				<code>
					2 => Guests
				</code> <br>
				<code>
					3 => Members
				</code> <br>
				<code>
					4 => Administrators
				</code> <br>
				<code>
					5 => Banned
				</code> <br>
				<code>
					6 => Moderators
				</code> <br>
			</div>
			<div class=" form-group col-md-2">
				<p>Number :</p>
				<select name="perm" class="form-control">
					@foreach($perm as $p)
			  			<option name="perm">{{ $p->id }}</option>
					@endforeach
				</select> <br>
				<button type="submit" class="btn btn-default">Save</button>
			</div>
		{{ Form::close() }}

	</div>
@endsection
