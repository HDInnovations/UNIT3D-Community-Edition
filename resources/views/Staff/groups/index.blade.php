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
                <th>ID</th>
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
            @foreach($groups as $group)
            <tr>
                <td>{{ $group->id }}</td>
                <td><a href="{{ route('staff_groups_edit', ['group' => $group->name, 'id' => $group->id]) }}">{{ $group->name }}</a></td>
                <td>{{ $group->position }}</td>
                <td><i class="fa fa-circle" style="color: {{ $group->color }};"></i> {{ $group->color }}</td>
                <td><i class="{{ $group->icon }}"></i> [{{ $group->icon }}]</td>
                <td>{{ $group->effect }}</td>
                <td>@if($group->is_internal == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->is_modo == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->is_admin == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->is_trusted == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->is_immune == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->is_freeleech == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
                <td>@if($group->autogroup == 0)<i class="fa fa-times text-red"></i>@else<i class="fa fa-check text-green"></i>@endif</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
@endsection
