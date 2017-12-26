@extends('layout.default')

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li>
  <a href="{{ route('staff_category_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Categories</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('staff_category_edit', array('slug' => $category->slug, 'id' => $category->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Torrent Category</span>
  </a>
</li>
@stop

@section('content')
<div class="container box">
        <h2>Edit A Category</h2>
        {{ Form::open(array('route' => array('staff_category_edit', 'slug' => $category->slug, 'id' => $category->id))) }}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="{{ $category->name }}">
            </div>
            <div class="form-group">
                <label for="name">Icon (FontAwesome)</label>
                <input type="text" class="form-control" name="icon" value="{{ $category->icon }}">
            </div>

            <button type="submit" class="btn btn-default">{{ trans('common.submit') }}</button>
        {{ Form::close() }}
</div>
@stop
