<div id="PrivilegePanel" xmlns:wire="http://www.w3.org/1999/xhtml"
     x-data="{ tab: 3, panel: 0, query: '', roleSlug: '',  activeUser: @entangle('activeUser') }">
    <div class="ppNav">
             <div :class="{ 'active': tab === 3 }" @click.prevent="tab = 3; panel = 0;"><i
                        class="fas fa-users"></i> Users</div>
            <div :class="{ 'active': tab === 1 }" @click.prevent="tab = 1; panel = 0;"><i
                        class="fas fa-users-class"></i> Roles</div>
            <div :class="{ 'active': tab === 2 }"@click.prevent="tab = 2; panel = 0;"><i
                        class="fas fa-key"></i> Privileges</div>
    </div>
    <div class="ppBody">
        <section x-show="tab === 3 && panel == 0">
            <div class="ppTable">
                <div class="ppTInfo">
                    <div class="ppTTitle"><strong>Configure Users</strong></div>
                    <div class="ppTInput">
                        <input type="text" wire:model="userSearch" style="width: 200px;" placeholder="@lang('user.search')"/>
                    </div>
                    <div class="ppTBack"><a href="{{route('staff.dashboard.index')}}"><i class="fas fa-arrow-circle-left"></i> Back to Dashboard</a></div>
                </div>
                <div class="ppTHeadUsers">
                    <div class="ppTHeadings" sortable wire:click="sortBy('username')"
                         :direction="$wire.sortField === 'username' ? $wire.sortDirection : null" role="button">
                        @lang('common.username')
                        @include('livewire.includes._sort-icon', ['field' => 'username'])
                    </div>
                    <div class="ppTHeadings ppBadgeRoles" sortable wire:click="sortBy('role_id')"
                         :direction="$wire.sortField === 'role_id' ? $wire.sortDirection : null" role="button">
                        Role(s)
                        @include('livewire.includes._sort-icon', ['field' => 'role_id'])</div>
                    <div class="ppTHeadings" sortable wire:click="sortBy('email')"
                         :direction="$wire.sortField === 'email' ? $wire.sortDirection : null"
                         role="button">
                        @lang('common.email')
                        @include('livewire.includes._sort-icon', ['field' => 'email'])</div>
                    <div class="ppTHeadings" sortable wire:click="sortBy('created_at')"
                         :direction="$wire.sortField === 'created_at' ? $wire.sortDirection : null"
                         role="button">
                        @lang('user.registration-date')
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </div>
                    <div class="ppTHeadings">
                        Actions
                    </div>
                </div>
                <div class="ppTBodyUsers">
                    @foreach ($users as $user)
                        <div class="ppBadge">
						<span class="text-bold"
                              style="color:{{ $user->primaryRole->color }}; background-image:{{ $user->primaryRole->effect }}; background-color: #1e1e1e">
                            <i class="{{ $user->primaryRole->icon }}"></i> {{ $user->username }}
                        </span>
                        </div>
                        <div class="ppBadge ppBadgeRoles">
                            @foreach($user->roles as $role)
                                @if($role->slug === $user->primaryRole->slug)
                                    <span class="text-bold"
                                          style="color:{{ $user->primaryRole->color }}; background-image:{{ $user->primaryRole->effect }}; background-color: #1e1e1e">
                                                    <i class="{{ $user->primaryRole->icon }}"></i> {{ $user->primaryRole->name }}</span>
                                @else
                                    <span class="ppBadgeSmall">{{$role->name}}</span>
                                @endif
                            @endforeach
                        </div>
                        <div class="ppValue">{{ $user->email }}</div>
                        <div class="ppValue">{{ \Illuminate\Support\Carbon::make($user->created_at)->toFormattedDateString() }}</div>
                        <div class="ppActions">
                            <a href="{{route('users.show', ['username' => $user->username])}}"><i class="far fa-id-card"></i> Profile</a>
                            <a href="{{route('user_setting', ['username' => $user->username])}}"><i class="fas fa-user-edit"></i> Edit</a>
                            <a href="#"
                               @click.prevent="$wire.GetUser({{$user->id}}).then( ()=>{ tab = 3; panel = 1; } );"><i class="fas fa-users-class"></i> Roles</a>
                            <a href="#"
                               @click.prevent="$wire.GetUser({{$user->id}}).then( ()=>{ tab = 3; panel = 2; } );"><i class="fas fa-key"></i> Privilges</a>

                        </div>
                    @endforeach
                </div>
                <div class="ppPaginate">
                    {{ $users->links() }}
                </div>
            </div>
        </section>

        <section x-show="tab === 1 && panel == 0">
            <div class="ppTable">
                <div class="ppTInfo">
                    <div class="ppTTitle"><strong>Configure Roles</strong></div>
                    <div class="ppTBack"><a href="{{route('staff.dashboard.index')}}"><i class="fas fa-arrow-circle-left"></i> Back to Dashboard</a></div>
                </div>
                <div class="ppTHead">
                    <div class="ppTHeadings">Role <small style="font-family: monospace; font-size: 9px;">[slug]</small></div>
                    <div class="ppTHeadings">Badge</div>
                    <div class="ppTHeadings">Actions</div>
                </div>
                <div class="ppTBody">
                @foreach($roles as $role)
                        <div class="ppRole">{{$role->name}} <small style="font-family: monospace; font-size: 10px;">[{{$role->slug}}]</small></div>
                        <div class="ppBadge"><span class="text-bold"
                                  style="color:{{ $role->color }}; background-image: {{ $role->effect }}; margin-bottom: 2px;">
                            <i class="{{ $role->icon }}" data-toggle="tooltip"
                               data-original-title="{{ $role->name }}"></i> {{$role->name}} </span></div>
                        <div class="ppActions">
                            <a href="#"><i class="fas fa-cog"></i> Settings</a>
                            <a @click.prevent="$wire.GetRolesPrivileges('{{$role->slug}}').then(() => { tab = 1; panel = 1; roleSlug = '{{$role->slug}}' })"
                               href="#"><i class="fas fa-key"></i> Privileges</a>
                        </div>
                @endforeach
                </div>
            </div>
        </section>
        <section x-show="tab === 2 && panel == 0">
            <div class="ppTable">
                <div class="ppTInfo">
                    <div class="ppTTitle"><strong>Configure Privileges</strong></div>
                    <div class="ppTBack"><a href="{{route('staff.dashboard.index')}}"><i class="fas fa-arrow-circle-left"></i> Back to Dashboard</a></div>
                </div>
                <div class="ppTHead">
                    <div class="ppTHeadings">Privilege <small style="font-family: monospace; font-size: 9px;">[slug]</small></div>
                    <div class="ppTHeadings">Description</div>
                    <div class="ppTHeadings">Actions</div>
                </div>
                <div class="ppTBody">
                @foreach($privileges as $privilege)
                        <div class="ppPrivilege">{{$privilege->name}} <small
                                    style="font-family: monospace; font-size: 10px;">[{{$privilege->slug}}]</small></div>
                        <div class="ppDescription">{{$privilege->description}}</div>
                        <div class="ppActions">
                            <a href="#">Edit Settings</a>
                            <a href="#">Audit Usage</a>
                        </div>
                @endforeach
                </div>
            </div>
        </section>

        <section x-show="tab === 1 && panel == 1">
            <div class="ppTable">
                <div class="ppTInfo">
                    <div class="ppTTitle"><strong>Configure <span class="text-bold"
                          style="color:{{ $Role?->color }}; background-image:{{ $Role?->effects }};">
                            <i class="{{ $Role?->icon }}" data-toggle="tooltip"
                               data-original-title="{{ $Role?->name }}"></i> {{$Role?->name}} </span> Role's Privileges</strong></div>
                    <div class="ppTBack"><div @click="tab = 1; panel = 0; roleSlug= ''"><i class="fas fa-arrow-circle-left"></i> Back to Roles</div></div>
                </div>
                <div class="ppTHead">
                    <div class="ppTHeadings">Privilege <small style="font-family: monospace; font-size: 9px;">[slug]</small></div>
                    <div class="ppTHeadings">Description</div>
                    <div class="ppTHeadings">Give Role Privilege?</div>
                </div>
                <div class="ppTBody">
                @foreach($privileges as $privilege)
                        <div class="ppPrivilege">
                            {{$privilege->name}}<small style="font-family: monospace; font-size: 9px;">[{{$privilege->slug}}]</small>
                        </div>
                        <div class="ppDescription">{{$privilege->description}}</div>
                        <div class="ppPermToggles" wire:key="{{$privilege->id}}">
                            <div
                               @click.prevent="$wire.GiveRolePrivilege( roleSlug ,'{{$privilege?->slug}}')"
                               class="ppYes {{ (!empty($RolesPrivileges) && $RolesPrivileges->contains($privilege) ? 'ppSelected' : 'ppNotSelected' ) }}">Yes
                            </div><div
                               @click.prevent="$wire.RemoveRolePrivilege( roleSlug ,'{{$privilege?->slug}}')"
                               class="ppNo {{ (!empty($RolesPrivileges) && $RolesPrivileges->contains($privilege) ? 'ppNotSelected' : ($RolesRestrictions?->contains($privilege) ? 'ppNotSelected' : 'ppSelected' )) }}">No
                            </div><div
                               @click.prevent="$wire.RestrictRolePrivilege( roleSlug ,'{{$privilege?->slug}}')"
                               class="ppNever {{ (!empty($RolesRestrictions) && $RolesRestrictions->contains($privilege) ? 'ppSelected' : 'ppNotSelected' ) }}">Never</div>
                        </div>
                @endforeach
                </div>
            </div>
        </section>

        <section x-show="tab === 3 && panel === 1">
            @if(isset($ActiveUser) && !empty($ActiveUser))
            <div class="ppTable">
                <div class="ppTInfo">
                    <div class="ppTTitle"><strong>Configure Roles for User: <span class="text-bold" style="color:{{ $ActiveUser->primaryRole->color }};">
                            <i class="{{ $ActiveUser->primaryRole->icon }}"></i> {{ $ActiveUser->username }}</span></strong></div>
                    <div class="ppTBack"><a href="#" @click.prevent="tab = 3; panel = 0;"><i class="fas fa-arrow-circle-left"></i> Back to Users</a></div>
                </div>
                <div class="ppTHeadRoles">
                    <div class="ppTHeadings">Role</div>
                    <div class="ppTHeadings">Give User Role?</div>
                    <div class="ppTHeadings4">Privileges</div>
                </div>
                <div class="ppTBodyRoles">
                    @foreach($roles as $role)
                        <div class="ppBadge">
                            <span wire:target="ActiveUser" style="background-image:{{ $role->effect }}; background-color: #1e1e1e">
                                <i style="color: {{$role->color}}" class="{{ $role->icon }}"></i>
                             {{$role->name}}
                            </span>
                        </div>
                        <div class="ppPermToggles" wire:key="{{$role->id}}">
                            <div
                                    @click.prevent="$wire.GiveUserRole( {{$ActiveUser->id}} ,'{{$role?->slug}}')"
                                    class="ppYes {{ $ActiveUser->roles->contains($role) ? 'ppSelected' : 'ppNotSelected' }}">Yes
                            </div>
                            <div
                                    @click.prevent="$wire.RemoveUserRole( {{$ActiveUser->id}} ,'{{$role?->slug}}')"
                                    class="ppNo {{ $ActiveUser->roles->contains($role) ? 'ppNotSelected' : 'ppSelected'  }}">No
                            </div>
                        </div>
                        <div class="ppDescription" style="font-family: monospace; font-size: 9px;">
                            @foreach($role->privileges as $priv)
                            {{$priv->slug}},
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </section>
        <section x-show="tab === 3 && panel === 2">
            @if(isset($ActiveUser) && !empty($ActiveUser))
                <div class="ppTable">
                    <div class="ppTInfo">
                        <div class="ppTTitle">
                            <strong>Configure Privileges for User: <span class="text-bold" style="color:{{ $ActiveUser->primaryRole->color }};">
                            <i class="{{ $ActiveUser->primaryRole->icon }}"></i> {{ $ActiveUser->username }}</span>
                            </strong>
                        </div>
                        <div class="ppTBack"><a href="#" @click.prevent="tab = 3; panel = 0;"><i class="fas fa-arrow-circle-left"></i> Back to Users</a></div>
                    </div>
                    <div class="ppTHead">
                        <div class="ppTHeadings">Privilege <small style="font-family: monospace; font-size: 9px;">[slug]</small></div>
                        <div class="ppTHeadings">Description</div>
                        <div class="ppTHeadings">Give User Privilege?</div>
                    </div>
                    <div class="ppTBody">
                    @foreach($privileges as $privilege)
                        <div class="ppPrivilege" @UpdatedUserPrivileges="$wire.refresh">
                            <span wire:target="ActiveUser">
                                {!! $ActiveUser->hasPrivilegeTo($privilege->slug) ?
                                '<i class="fas fa-check-square" style="color: #0ba360" pp-tooltip="Privilege Granted"></i>'
                                : '<i class="fas fa-times-square" style="color: #ef1c1c" pp-tooltip="Privilege Denied"></i>'
                                !!} {{$privilege->name}}
                            </span>
                            <small style="font-family: monospace; font-size: 9px;">[{{$privilege->slug}}]</small>

                        </div>
                        <div class="ppDescription">{{$privilege->description}}</div>
                        <div class="ppPermToggles" wire:key="{{$privilege->id}}">
                            <div pp-tooltip="Provides the Privilege to the User"
                                    @click.prevent="$wire.GiveUserPrivilege( {{$ActiveUser->id}} ,'{{$privilege?->slug}}')"
                                    class="ppYes {{ $ActiveUser->privileges->contains($privilege) ? 'ppSelected' : 'ppNotSelected' }}">Yes
                            </div>
                            <div pp-tooltip="Does not provide the Privilege to the User"
                                    @click.prevent="$wire.RemoveUserPrivilege( {{$ActiveUser->id}} ,'{{$privilege?->slug}}')"
                                    class="ppNo {{ $ActiveUser->privileges->contains($privilege) ? 'ppNotSelected' : ($ActiveUser->UserRestrictedPrivileges->contains($privilege) ? 'ppNotSelected' : 'ppSelected' ) }}">No
                            </div>
                            <div pp-tooltip="Restricts the User from being Provided this Privilege By a Role."
                                    @click.prevent="$wire.RestrictUserPrivilege( {{$ActiveUser->id}} ,'{{$privilege?->slug}}')"
                                    class="ppNever {{ ($ActiveUser->UserRestrictedPrivileges->contains($privilege) ? 'ppSelected' : 'ppNotSelected' ) }}">Never
                            </div>
                            <div class="ppByRole
                                {{ $ActiveUser->hasPrivilegeThroughRole($privilege) ? 'ppSelected' :
                                ($ActiveUser->hasRestrictedPrivilegeThroughRole($privilege) ? 'ppRestricted' : 'ppNotSelected') }}"
                                 pp-tooltip="{{ $ActiveUser->hasPrivilegeThroughRole($privilege) ? 'Has Privilege Through Role' :
                                ($ActiveUser->hasRestrictedPrivilegeThroughRole($privilege) ? 'Privilege is Restricted by a Role' : '') }}"
                            ><i class="fas fa-users-class"></i>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            @endif
        </section>

    </div>


</div>