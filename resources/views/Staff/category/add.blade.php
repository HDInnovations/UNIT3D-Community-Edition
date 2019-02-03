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
        <a href="{{ route('staff_category_add_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add Torrent Category</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add A Category</h2>
        <form role="form" method="POST" action="{{ route('staff_category_add') }}">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label for="name">Position</label>
            <input type="text" class="form-control" name="position">
        </div>
        <div class="form-group">
            <label for="name">Icon (FontAwesome)</label>
            <input type="text" class="form-control" name="icon" placeholder="Example: {{ config('other.font-awesome') }} fa-rocket">
        </div>
        <label for="sidenav" class="control-label">Has Meta Data? (Movie/TV)</label>
        <div class="radio-inline">
            <label><input type="radio" name="meta" checked value="1">Yes</label>
        </div>
        <div class="radio-inline">
            <label><input type="radio" name="meta" value="0">No</label>
        </div>
        <br>
        <br>
        <button type="submit" class="btn btn-default">@lang('common.add')</button>
        </form>
    </div>
@endsection
