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
  <a href="{{ route('staff_type_edit', array('slug' => $type->slug, 'id' => $type->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Torrent Type</span>
  </a>
</li>
@endsection

@section('content')
<div class="container box">
        <h2>Edit A Torrent Type</h2>
        {{ Form::open(array('route' => array('staff_type_edit', 'slug' => $type->slug, 'id' => $type->id))) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $type->name }}">
            </div>

            <div class="form-group">
                <label for="name">Position</label>
                <input type="text" class="form-control" name="position" value="{{ $type->position }}">
            </div>

            <button type="submit" class="btn btn-default">{{ trans('common.submit') }}</button>
        {{ Form::close() }}
</div>
@endsection
