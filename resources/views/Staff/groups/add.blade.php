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
  <a href="{{ route('staff_groups_add') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Add User Group</span>
  </a>
</li>
@endsection

@section('content')
<center><h3>Add New Group</h3></center>
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
        {{ Form::open(['route' => 'staff_groups_add'])}}
        <td><input type="text" name="group_name" value="" placeholder="Name" class="form-control" /></td>
        <td><input type="number" name="group_postion" value="" placeholder="Position" class="form-control" /></td>
        <td><input type="text" name="group_color" value="" placeholder="HEX Color ID" class="form-control" /></td>
        <td><input type="text" name="group_icon" value="" placeholder="FontAwesome Icon" class="form-control" /></td>
        <td><input type="text" name="group_effect" value="" placeholder="GIF Effect" class="form-control" /></td>
        <td>
            {{ Form::checkbox('group_internal', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('group_modo', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('group_admin', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('group_trusted', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('group_immune', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('group_freeleech', '1', false) }}
        </td>
        <td>
            {{ Form::checkbox('autogroup', '1', false) }}
        </td>
      </tr>
  </tbody>
</table>
<button type="submit" class="btn btn-primary">{{ trans('common.add') }}</button>
{{ Form::close() }}
</div>
</div>
@endsection
