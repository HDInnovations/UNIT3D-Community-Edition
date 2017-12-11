@extends('layout.default')

@section('content')
<div class="container box">
    <h2>Groups</h2>
    <a href="{{ route('staff_groups_add') }}" class="btn btn-primary">Add New Group</a>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Color</th>
              <th>Icon</th>
              <th>Modo</th>
              <th>Admin</th>
              <th>Trusted</th>
              <th>Immune</th>
            </tr>
          </thead>
          <tbody>
            @foreach($groups as $group)
            <tr>
              <td><a href="{{ route('staff_groups_edit', ['group' => $group->name, 'id' => $group->id]) }}">{{ $group->name }}</a></td>
              <td>{{ $group->color }}</td>
              <td>{{ $group->icon }}</td>
              <td>{{ $group->is_modo }}</td>
              <td>{{ $group->is_admin }}</td>
              <td>{{ $group->is_trusted }}</td>
              <td>{{ $group->is_immune }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
@stop
