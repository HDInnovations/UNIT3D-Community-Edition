@extends('layout.default')

@section('breadcrumb')
	<li>
		<a href="{{ route('roles.index') }}" itemprop="url"
		   class="l-breadcrumb-item-link">
			<span itemprop="title" class="l-breadcrumb-item-link-title">Roles Info</span>
		</a>
	</li>
@endsection

@section('content')
	<style>
		.td {
			display:table-cell;
		}s
	</style>
	<div class="container box">
		<div class="header gradient light_blue">
			<div class="inner_content">
				<h1>User Roles</h1>
			</div>
		</div>
		<div class="alert alert-info text-center" style="">
			<p style="">Members may advance their role on site by meeting specific requirements</p>
			<div style="">
				Some roles are manually assigned by staff to members who go above and beyond with their contributions to the site.
			</div>
		</div>

		<div class="" style="text-align: left; padding-left: 0; padding-right: 0;">
			@foreach ($roles as $role)
				@if($role->id === $user->role_id)
					<div class="row block alert alert-info table" style="width: 100%; margin-bottom: 0 !important;">
				@else
					<div class="row block" style="width: 100%; background-color: inherit; margin-bottom: 0 !important;">
				@endif
				<div class="col-md-3 well" style="display: table-cell;">
					<div style="width: 98%; padding: 10px; margin: 6px auto auto auto; height: 100%; border-radius: 2px; border: 1px solid transparent;">
						<div style="width: 100%; margin: 6px auto 6px auto; text-align: center;">
							<div style="padding-bottom: 6px; margin-bottom: 6px; border-bottom: 1px solid #333333;">
								<h4 style="color:{{ $group->color }}; font-size: 13px; margin-bottom: 0px; text-transform: uppercase;">{{ $role->name }}</h4>
							</div>
							<div style="padding: 16px 0;"><i class="{{ $role->icon }}" style="color:{{ $role->color }}; font-size: 36px;"></i></div>
							<div style="padding-top: 6px; margin-top: 6px; border-top: 1px solid #333333;"></div>
							@if($role->tagline !== null)
								<div class="font-stat">{{ $role->tagline }}</div>
							@else
								<div class="font-stat">Not Set</div>
							@endif
						</div>
						<div style="border-top: 1px solid #333333; padding-top: 6px; margin-top: 6px;"></div>
						<div style="text-align: center;" class="font-stat"><span style="font-weight: bold;">{{ $role->users()->withTrashed()->count() }}</span> members</div>
					</div>
				</div>

				<div class="col-md-5" style="display: table-cell;">
					<div style="background-color: inherit; margin-bottom: 0 !important;">
						<div class="td " style="width: 45%;">
							<div style="width: 90%; margin: auto;">
								<div style="padding-bottom: 6px; margin-bottom: 6px; border-bottom: 1px solid #333333;">
									<h4 style="font-size: 13px; margin-bottom: 0px; text-transform: uppercase;">Role Rules</h4>
								</div>
								@if($role->rule_id === null)
									<div>Staff Assigned</div>
									<div style="border-top: 1px solid #333333; padding-top: 6px; margin-top: 6px;"></div>
								@endif
								@if($role->description !== null)
									<div>{{ $role->description }}</div>
								@endif
								@if($role->rule_id !== null)
									@foreach($role->rules as $rule)
										<div class="row" style="background-color: inherit; margin-bottom: 0 !important;">
											<div class="col-md-5">{{ $rule->name }}</div>
											<div class="col-md-7"><span class="pull-right">{{ $rule->value }} | <i class="fal fa-check text-green"></i></span></div>
										</div>
									@endforeach
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4" style="display: table-cell;">
					<div style="margin: auto;">
						<div style="padding-bottom: 6px; margin-bottom: 6px; border-bottom: 1px solid #333333;">
							<h4 style="font-size: 13px; margin-bottom: 0px; text-transform: uppercase;">Buffs</h4>
						</div>
						@foreach($role->buffs as $buff)
						<div class="row">
							<span><i class="fal fa-check text-green"></i> | </span>
							<span>{{ $buff->name }}</span>
						</div>
					</div>
				</div>
			</div>
			@endforeach

			<div style="padding-top: 6px; margin-top: 6px; border-top: 1px solid #333333;">
				<div class="text-center" style="font-size: 13px; color: #666666;">
					<strong>Completed Seeds refers to the all time count of torrents that you have seeded for at least seven days minimum.</strong><br>
					Roles can be attained and lost depending on if you meet the roles specified rules.<br>
					Any buffs attached to role are available to you as long as you retain said role.
				</div>
			</div>
		</div>
	</div>
@endsection