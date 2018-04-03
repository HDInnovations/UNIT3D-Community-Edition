@extends('layout.default')

@section('title')
<title>{{ trans('common.members') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ trans('user.members-desc', ['title' => config('other.title')]) }}">
@endsection

@section('breadcrumb')
<li>
	<a href="{{ route('members') }}" itemprop="url" class="l-breadcrumb-item-link">
		<span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.members') }}</span>
	</a>
</li>
@endsection


@section('content')
<div class="box container">
	<div class="profil">
		<div class="header gradient silver">
			<div class="inner_content">
				<div class="page-title"><h1>{{ trans('common.members') }}</h1></div>
			</div>
		</div>
		<form action="{{route('userSearch')}}" method="any">
		<input type="text" name="username" id="username" size="25" placeholder="{{ trans('user.search') }}" class="form-control" style="float:right;">
		</form>
		  <table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>{{ trans('user.image') }}</th>
					<th>{{ trans('common.username') }}</th>
					<th>{{ trans('common.group') }}</th>
					<th>{{ trans('user.registration-date') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($users as $user)
					<tr>
						<td>
							@if($user->image != null)
								<img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}" class="members-table-img img-thumbnail">
							@else
								<img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}" class="members-table-img img-thumbnail">
							@endif
						</td>
						<td><a href="{{ route('profile', ['username' => $user->username, 'id' => $user->id]) }}">{{ $user->username }}</a></td>
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
@endsection
