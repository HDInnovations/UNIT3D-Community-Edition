@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.staff-dashboard') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.resolutions.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('staff.torrent-resolutions') }}</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.resolutions.edit', ['id' => $resolution->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('common.edit') }} Torrent Resolution</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.edit') }} A Torrent Resolution</h2>
        <form role="form" method="POST" action="{{ route('staff.resolutions.update', ['id' => $resolution->id]) }}">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">{{ __('common.name') }}</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $resolution->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="name">{{ __('common.position') }}</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $resolution->position }}">
                </label>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.submit') }}</button>
        </form>
    </div>
@endsection
