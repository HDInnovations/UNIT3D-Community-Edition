<div>
	<div class="mb-10 form-inline pull-right">
		<div class="form-group">
			Per Page: &nbsp;
			<select wire:model="perPage" class="form-control">
				<option>25</option>
				<option>50</option>
				<option>100</option>
			</select>
		</div>

		<div class="form-group">
			<input type="text" wire:model="searchTerm" class="form-control" style="width: 275px;" placeholder="Search By Username Or Email"/>
		</div>
	</div>
	<div class="box-body no-padding">
		<table class="table vertical-align table-hover">
			<tbody>
			<tr>
				<th>Avatar</th>
				<th>
					<div sortable wire:click="sortBy('username')" :direction="$sortField === 'username' ? $sortDirection : null" role="button">
						Username
						@include('livewire.includes._sort-icon', ['field' => 'username'])
					</div>
				</th>
				<th>
					<div sortable wire:click="sortBy('group_id')" :direction="$sortField === 'group_id' ? $sortDirection : null" role="button">
						Role
						@include('livewire.includes._sort-icon', ['field' => 'group_id'])
					</div>
				</th>
				<th class="hidden-sm hidden-xs">
					<div sortable wire:click="sortBy('email')" :direction="$sortField === 'email' ? $sortDirection : null"
					     role="button">
						Email
						@include('livewire.includes._sort-icon', ['field' => 'email'])
					</div>
				</th>
				<th class="hidden-sm hidden-xs">
					<div sortable wire:click="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null"
					     role="button">
						Registered
						@include('livewire.includes._sort-icon', ['field' => 'created_at'])
					</div>
				</th>
				<th>Action</th>
			</tr>
			@foreach ($users as $user)
				<tr>
					<td>
						@if ($user->image != null)
							<img src="{{ url('files/img/' . $user->image) }}" alt="{{ $user->username }}"
							     class="img-circle" style="width: 40px; height: 40px;">
						@else
							<img src="{{ url('img/profile.png') }}" alt="{{ $user->username }}"
							     class="img-circle" style="width: 40px; height: 40px;">
						@endif
					</td>
					<td>
						<span class="badge-user text-bold">
                            {{ $user->username }}
							@if ($user->warnings_count > 0)
								<i class="{{ config('other.font-awesome') }} fa-exclamation-circle text-orange" aria-hidden="true"
								   data-toggle="tooltip" data-original-title="@lang('common.active-warning')"></i>
							@endif
                        </span>
					</td>
					<td>
						<span class="badge-user text-bold"
						      style="color:{{ $user->group->color }}; background-image:{{ $user->group->effect }};">
                            <i class="{{ $user->group->icon }}"></i>
                            {{ $user->group->name }}
                        </span>
					</td>
					<td class="hidden-sm hidden-xs">{{ $user->email }}</td>
					<td class="hidden-sm hidden-xs">{{ $user->created_at }}</td>
					<td>
						<div class="dropdown">
							<a class="dropdown-toggle btn btn-default btn-xs" data-toggle="dropdown" href="#" aria-expanded="true">
								Actions
								<i class="fas fa-caret-circle-right"></i>
							</a>
							<ul class="dropdown-menu">
								<li role="presentation">
									<a role="menuitem" tabindex="-1" target="_blank"
									   href="{{ route('users.show', ['username' => $user->username]) }}">View Profile</a>
								</li>
								<li role="presentation">
									<a role="menuitem" tabindex="-1"
									   href="{{ route('user_setting', ['username' => $user->username, 'id' => $user->id]) }}">Edit User</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
		@if (! $users->count())
			<div class="margin-10">
				No Results Found!
			</div>
		@endif
		<br>
		<div class="text-center">
			{{ $users->links() }}
		</div>
	</div>
</div>
