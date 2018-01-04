@extends('layout.default')

@section('title')
<title>{{ trans('common.members') }} - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="List of users registered on {{ Config::get('other.title') }} with all groups. Find an user now.">
@stop

@section('breadcrumb')
<li>
	<a href="{{ route('members') }}" itemprop="url" class="l-breadcrumb-item-link">
		<span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.members') }}</span>
	</a>
</li>
@stop


@section('content')
<div class="box container">
	<div class="profil">
		<div class="header gradient silver">
			<div class="inner_content">
				<div class="page-title"><h1>{{ trans('common.members') }}</h1></div>
			</div>
		</div>
		<form action="{{route('userSearch')}}" method="any">
		<input type="text" name="username" id="username" size="25" placeholder="Quick Search by Username" class="form-control" style="float:right;">
		</form>
		  <table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Image</th>
					<th>Username</th>
					<th>Group</th>
					<th>Registration date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td>
							@if($user->image != null)
								<img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}" class="members-table-img img-thumbnail">
							@else
								<img src="{{ url('img/profil.png') }}" alt="{{ $user->username }}" class="members-table-img img-thumbnail">
							@endif
						</td>
						<td><a href="{{ route('profil', ['username' => $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a></td>
						<td>{{ $user->group->name }}</td>
						<td>{{ date('d M Y', strtotime($user->created_at)) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="col-md-12">
		{{ $users->links() }}
	</div>
</div>
@stop
