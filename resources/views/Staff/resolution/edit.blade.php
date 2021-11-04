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
        <a href="{{ route('staff.resolutions.edit', ['id' => $resolution->id]) }}" itemprop="url"
            class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.edit') Torrent Resolution
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.edit') A Torrent Resolution
        </h2>
        <form role="form" method="POST" action="{{ route('staff.resolutions.update', ['id' => $resolution->id]) }}">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $resolution->name }}">
            </div>

            <div class="form-group">
                <label for="position">@lang('common.position')</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ $resolution->position }}">
            </div>

            <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection
