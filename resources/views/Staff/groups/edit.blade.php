@extends('layout.default')

@section('content')
<center><h3>{{ $group->name }} Permissions</h3></center>
<div class="container box">
<div class="table-responsive">
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Name</th>
      <th>Color</th>
      <th>Icon</th>
      <th>Is Modo</th>
      <th>Is Admin</th>
      <th>Is Trusted</th>
      <th>Is Immune</th>
    </tr>
  </thead>

  <tbody>
      <tr>
        {{ Form::open(array('route' => ['staff_groups_edit', 'group' => $group->name, 'id' => $group->id], 'method' => 'post')) }}
        <td><input type="text" name="group_name" value="{{ $group->name }}" size="10" /></td>
        <td><input type="text" name="group_color" value="{{ $group->color }}" size="10" /></td>
        <td><input type="text" name="group_icon" value="{{ $group->icon }}" size="10" /></td>
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
      </tr>
  </tbody>
</table>
<button type="submit" class="btn btn-primary">Save</button>
{{ Form::close() }}
</div>
</div>
@stop
