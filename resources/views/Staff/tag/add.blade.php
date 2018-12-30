@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff_tag_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Torrent Tags</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff_tag_add_form') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Add Torrent Tag</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>Add A Torrent Tag (Genre)</h2>
        <form role="form" method="POST" action="{{ route('staff_tag_add') }}">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name">
        </div>

        <button type="submit" class="btn btn-default">{{ trans('common.add') }}</button>
        </form>
    </div>
@endsection
