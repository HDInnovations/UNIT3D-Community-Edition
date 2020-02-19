@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.tags.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('torrent.torrent') @lang('torrent.genre-tags') (@lang('torrent.genre'))
            </span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.tags.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.add') @lang('torrent.torrent') @lang('torrent.genre-tags') (@lang('torrent.genre'))
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.add') @lang('torrent.torrent') @lang('torrent.genre-tags') (@lang('torrent.genre'))
        </h2>
        <form role="form" method="POST" action="{{ route('staff.tags.store') }}">
            @csrf
    
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name">
                </label>
            </div>
    
            <button type="submit" class="btn btn-default">{{ trans('common.add') }}</button>
        </form>
    </div>
@endsection
