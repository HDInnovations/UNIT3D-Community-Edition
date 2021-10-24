<div id="PrivilegePanel" xmlns:wire="http://www.w3.org/1999/xhtml"
     x-data="{ tab: 1, panel: 0, query: '', roleSlug: '',  activeUser: @entangle('activeUser') }">
    <div class="ppNav">
            <div :class="{ 'active': tab === 1 }" @click.prevent="tab = 1; panel = 0;"><i
                        class="fas fa-user-tag"></i> Roles</div>
            <div :class="{ 'active': tab === 2 }"@click.prevent="tab = 2; panel = 0;"><i
                            class="fas fa-lock"></i> Privileges</div>
            <div :class="{ 'active': tab === 3 }" @click.prevent="tab = 3; panel = 0;"><i
                            class="fas fa-user-lock"></i> Users</div>
    </div>
    <div class="ppBody">
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
                                  style="color:{{ $role->color }}; background-image:{{ $role->effects }}; margin-bottom: 2px;">
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
            <table class="table">
                <thead>
                <tr>
                    <th>Privilege <small style="font-family: monospace; font-size: 10px;">[slug]</small></th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($privileges as $privilege)
                    <tr>
                        <td>{{$privilege->name}} <small
                                    style="font-family: monospace; font-size: 10px;">[{{$privilege->slug}}]</small></td>
                        <td>{{$privilege->description}}</td>
                        <td><a class="btn btn-success" href="#">Edit Settings</a> <a class="btn btn-primary" href="#">Audit
                                Usage</a></td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </section>
        <section x-show="tab === 3 && panel == 0">

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <input type="text" wire:model="userSearch" class="form-control float-right"
                               style="width: 275px;"
                               placeholder="@lang('user.search')"/>
                    </div>
                </div>
            </div>


            <table class="table vertical-align table-hover">
                <tbody>
                <tr>

                    <th>
                        <div sortable wire:click="sortBy('username')"
                             :direction="$wire.sortField === 'username' ? $wire.sortDirection : null" role="button">
                            @lang('common.username')
                            @include('livewire.includes._sort-icon', ['field' => 'username'])
                        </div>
                    </th>
                    <th>
                        <div sortable wire:click="sortBy('role_id')"
                             :direction="$wire.sortField === 'role_id' ? $wire.sortDirection : null" role="button">
                            Role
                            @include('livewire.includes._sort-icon', ['field' => 'role_id'])
                        </div>
                    </th>
                    <th class="hidden-sm hidden-xs">
                        <div sortable wire:click="sortBy('email')"
                             :direction="$wire.sortField === 'email' ? $wire.sortDirection : null"
                             role="button">
                            @lang('common.email')
                            @include('livewire.includes._sort-icon', ['field' => 'email'])
                        </div>
                    </th>
                    <th class="hidden-sm hidden-xs">
                        <div sortable wire:click="sortBy('created_at')"
                             :direction="$wire.sortField === 'created_at' ? $wire.sortDirection : null"
                             role="button">
                            @lang('user.registration-date')
                            @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                        </div>
                    </th>
                    <th>@lang('common.action')</th>
                </tr>
                @foreach ($users as $user)
                    <tr>
                        <td>
						<span class="badge-user text-bold">
                            {{ $user->username }}
                        </span>
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                                @if($role->slug === $user->primaryRole->slug)
                                    <span class="badge text-bold"
                                          style="color:{{ $user->primaryRole->color }}; background-image:{{ $user->primaryRole->effect }}; background-color: #1e1e1e">
                                                    <i class="{{ $user->primaryRole->icon }}"></i>{{ $user->primaryRole->name }}</span>
                                @else
                                    <span class="badge">{{$role->name}}</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="hidden-sm hidden-xs">{{ $user->email }}</td>
                        <td class="hidden-sm hidden-xs">{{ \Illuminate\Support\Carbon::make($user->created_at)->toFormattedDateString() }}</td>
                        <td>
                            <a class="btn btn-success" href="#"
                               @click.prevent="$wire.GetUser({{$user->id}}).then( ()=>{ tab = 3; panel = 2; } );">Edit
                                Privileges & Restrictions</a>
                            <a class="btn btn-primary" href="#">Change Primary Role</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

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
                    <div class="ppTHeadings">Status</div>
                </div>
                <div class="ppTBody">
                @foreach($privileges as $privilege)
                        <div class="ppPrivilege">{{$privilege->name}}<small
                                    style="font-family: monospace; font-size: 9px;">[{{$privilege->slug}}]</small></div>
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
        <section x-show="tab === 3 && panel === 2">
            @if(isset($ActiveUser) && !empty($ActiveUser))
                <div>
                    <p>Username: {{ $ActiveUser->username }} User ID: {{ $ActiveUser->id }} </p>
                </div>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Privilege <small style="font-family: monospace; font-size: 9px;">[slug]</small></th>
                        <th>Description</th>
                        <th style="min-width: 250px;">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($privileges as $privilege)
                        <tr>
                            <td>{{$privilege->name}}<small
                                        style="font-family: monospace; font-size: 9px;">[{{$privilege->slug}}]</small>
                            </td>
                            <td>{{$privilege->description}}</td>
                            <td>
                                <a href="#" style="margin-right: 10px;"
                                   @click.prevent="$wire.GiveUserPrivilege({{$ActiveUser->id}}, '{{$privilege->slug}}')"
                                   class="btn btn-xs {{ $ActiveUser->hasPrivilegeTo($privilege->slug) && !$ActiveUser->hasPrivilegeThroughRole($privilege) ? 'btn-success selected' : 'btn-primary not-selected'}}" {{ $ActiveUser->hasPrivilegeThroughRole($privilege) ? 'disabled' : '' }}>User
                                    Level</a>
                                <a href="#" style="margin-right: 10px;"
                                   class="btn btn-xs {{ $ActiveUser->hasPrivilegeThroughRole($privilege) ? 'btn-success selected' : 'btn-primary not-selected'}}">By
                                    Role</a>
                                <a href="#" style="margin-right: 10px;" class="btn btn-xs btn-primary not-selected">Never</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>


</div>