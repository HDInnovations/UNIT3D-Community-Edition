@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('staff.staff-dashboard')
            </span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.resolutions.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('staff.torrent-resolutions')
            </span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.resolutions.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                Add Torrent Resolution
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            Add A Torrent Resolution
        </h2>
        <form role="form" method="POST" action="{{ route('staff.resolutions.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="position">@lang('common.position')</label>
                <input type="text" class="form-control" id="position" name="position">
            </div>

            <button type="submit" class="btn btn-default">@lang('common.add')</button>
        </form>
    </div>
@endsection
