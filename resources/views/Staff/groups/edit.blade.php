@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li>
  <a href="{{ route('staff_groups_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">User Groups</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_groups_edit', array('group' => $group->name, 'id' => $group->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Edit User Group</span>
  </a>
</li>
@endsection

@section('content')
<center><h3>{{ $group->name }} Permissions</h3></center>
<div class="container box">
<div class="table-responsive">
<table class="table table-bordered table-hover">
  <thead>
    <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Color</th>
        <th>Icon</th>
        <th>Effect</th>
        <th>Internal</th>
        <th>Modo</th>
        <th>Admin</th>
        <th>Trusted</th>
        <th>Immune</th>
        <th>Freeleech</th>
        <th>Autogroup</th>
    </tr>
  </thead>

  <tbody>
      <tr>
        {{ Form::open(array('route' => ['staff_groups_edit', 'group' => $group->name, 'id' => $group->id], 'method' => 'post')) }}
        <td><input type="text" name="group_name" value="{{ $group->name }}" class="form-control" /></td>
        <td><input type="text" name="group_postion" value="{{ $group->position }}" class="form-control" /></td>
        <td><input type="text" name="group_color" value="{{ $group->color }}" class="form-control" /></td>
        <td><input type="text" name="group_icon" value="{{ $group->icon }}" class="form-control" /></td>
        <td><input type="text" name="group_effect" value="{{ $group->effect }}" class="form-control" /></td>
        <td>
          @if($group->is_internal == 1)
            {{ Form::checkbox('group_internal', '1', true) }}
          @else
            {{ Form::checkbox('group_internal', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->is_modo == 1)
            {{ Form::checkbox('group_modo', '1', true) }}
          @else
            {{ Form::checkbox('group_modo', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->is_admin == 1)
            {{ Form::checkbox('group_admin', '1', true) }}
          @else
            {{ Form::checkbox('group_admin', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->is_trusted == 1)
            {{ Form::checkbox('group_trusted', '1', true) }}
          @else
            {{ Form::checkbox('group_trusted', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->is_immune == 1)
            {{ Form::checkbox('group_immune', '1', true) }}
          @else
            {{ Form::checkbox('group_immune', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->is_freeleech == 1)
            {{ Form::checkbox('group_freeleech', '1', true) }}
          @else
            {{ Form::checkbox('group_freeleech', '0', false) }}
          @endif
        </td>
        <td>
          @if($group->autogroup == 1)
            {{ Form::checkbox('autogroup', '1', true) }}
          @else
            {{ Form::checkbox('autogroup', '0', false) }}
          @endif
        </td>
      </tr>
  </tbody>
</table>
<button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
{{ Form::close() }}
</div>
</div>
@endsection
