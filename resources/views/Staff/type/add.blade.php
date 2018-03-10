@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li>
  <a href="{{ route('staff_type_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Types</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_type_add') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Add Torrent Type</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
        <h2>Add A Torrent Type</h2>
        {{ Form::open(array('route' => 'staff_type_add')) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="form-group">
                <label for="name">Position</label>
                <input type="text" class="form-control" name="position">
            </div>

            <button type="submit" class="btn btn-default">{{ trans('common.add') }}</button>
        {{ Form::close() }}
</div>
@endsection
