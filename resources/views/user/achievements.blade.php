@extends('layout.default')

@section('breadcrumb')
<li class="active"><a href="{{ route('achievements') }}" itemprop="url"
	class="l-breadcrumb-item-link"> <span itemprop="title"
		class="l-breadcrumb-item-link-title">{{ trans('user.achievements') }}</span>
</a></li>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('user.unlocked-achievements') }}</div>
				<div class="panel-body">
					<br />
					<div class="table-responsive">
						<table class="table table-borderless">
							<thead>
								<tr>
									<th>{{ trans('common.name') }}</th>
									<th>{{ trans('common.description') }}</th>
									<th>{{ trans('common.progress') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($achievements as $item)
								<tr>
									<td><img src="/img/badges/{{ $item->details->name }}.png"
										data-toggle="tooltip" title=""
										data-original-title="{{ $item->details->name }}"></td>
									<td>{{ $item->details->description }}</td>
									@if($item->isUnlocked())
									<td><span class="label label-success">{{ trans('user.unlocked')
											}}</span></td> @else
									<td><span class="label label-warning">{{
											trans('common.progress') }}:
											{{$item->points}}/{{$item->details->points}}</span></td>
									@endif
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4 text-center">
			<div class="text-green well well-sm">
                <?php $unlocked = auth()->user()->unlockedAchievements()->count() ?>
                    <h3>
					<strong>{{ trans('user.unlocked-achievements') }}:</strong>{{
					$unlocked }}
				</h3>
			</div>
			<div class="text-red well well-sm">
                <?php $lock = auth()->user()->lockedAchievements()->count() ?>
                    <h3>
					<strong>{{ trans('user.locked-achievements') }}:</strong>{{ $lock
					}}
				</h3>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('user.pending-achievements') }}</div>
				<div class="panel-body">
					<br />
					<div class="table-responsive">
						<table class="table table-borderless">
							<thead>
								<tr>
									<th>{{ trans('common.name') }}</th>
									<th>{{ trans('common.description') }}</th>
									<th>{{ trans('common.progress') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($pending as $p)
								<tr>
									<td><img src="/img/badges/{{ $p->details->name }}.png"
										data-toggle="tooltip" title=""
										data-original-title="{{ $p->details->name }}"></td>
									<td>{{ $p->details->description }}</td>
									<td><span class="label label-warning">{{
											trans('common.progress') }}:
											{{$p->points}}/{{$p->details->points}}</span></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">{{ trans('user.locked-achievements') }}</div>
				<div class="panel-body">
					<br />
					<div class="table-responsive">
						<table class="table table-borderless">
							<thead>
								<tr>
									<th>{{ trans('common.name') }}</th>
									<th>{{ trans('common.description') }}</th>
									<th>{{ trans('common.progress') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($locked as $l)
								<tr>
									<td><img src="/img/badges/{{ $l->details->name }}.png"
										data-toggle="tooltip" title=""
										data-original-title="{{ $l->details->name }}"></td>
									<td>{{ $l->details->description }}</td>
									<td><span class="label label-danger">{{ trans('user.locked') }}</span></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
