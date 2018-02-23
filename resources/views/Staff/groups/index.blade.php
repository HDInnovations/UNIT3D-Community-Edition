@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_groups_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">User Groups</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
    <h2>Groups</h2>
    <a href="{{ route('staff_groups_add') }}" class="btn btn-primary">Add New Group</a>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Color</th>
                <th>Icon</th>
                <th>Effect</th>
                <th>Modo</th>
                <th>Admin</th>
                <th>Trusted</th>
                <th>Immune</th>
                <th>Freeleech</th>
                <th>Autogroup</th>
            </tr>
          </thead>
          <tbody>
            @foreach($groups as $group)
            <tr>
              <td><a href="{{ route('staff_groups_edit', ['group' => $group->name, 'id' => $group->id]) }}">{{ $group->name }}</a></td>
              <td>{{ $group->position }}</td>
              <td>{{ $group->color }}</td>
              <td>{{ $group->icon }}</td>
              <td>{{ $group->effect }}</td>
              <td>{{ $group->is_modo }}</td>
              <td>{{ $group->is_admin }}</td>
              <td>{{ $group->is_trusted }}</td>
              <td>{{ $group->is_immune }}</td>
              <td>{{ $group->is_freeleech }}</td>
              <td>{{ $group->autogroup }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
@endsection
