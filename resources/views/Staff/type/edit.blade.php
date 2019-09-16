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
        <a href="{{ route('staff_type_edit_form', ['slug' => $type->slug, 'id' => $type->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Edit Torrent Type</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Edit A Torrent Type</h2>
        <form role="form" method="POST" action="{{ route('staff_type_edit', ['slug' => $type->slug, 'id' => $type->id]) }}">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <label>
                <input type="text" class="form-control" name="name" value="{{ $type->name }}">
            </label>
        </div>

        <div class="form-group">
            <label for="name">Position</label>
            <label>
                <input type="text" class="form-control" name="position" value="{{ $type->position }}">
            </label>
        </div>

        <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection
