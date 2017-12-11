@extends('layout.default')

@section('content')
<center><h3>Add New Group</h3></center>
<div class="container box">
<div class="table-responsive">
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th>Name</th>
      <th>Color</th>
      <th>Icon</th>
    </tr>
  </thead>

  <tbody>
      <tr>
        {{ Form::open(['route' => 'staff_groups_add'])}}
        <td><input type="text" name="group_name" placeholder="Name" size="10" /></td>
        <td><input type="text" name="group_color" placeholder="HEX Color ID" size="10" /></td>
        <td><input type="text" name="group_icon" placeholder="FontAwesome Icon" size="10" /></td>
      </tr>
  </tbody>
</table>
<button type="submit" class="btn btn-primary">Save</button>
{{ Form::close() }}
</div>
</div>
@stop
