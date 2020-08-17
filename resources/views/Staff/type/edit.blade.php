@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('staff.types.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.torrent-types')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.types.edit', ['id' => $type->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                @lang('common.edit')
                @lang('torrent.torrent')
                @lang('common.type')
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            @lang('common.edit')
            @lang(trans_choice('common.a-an-art',false))
            @lang('torrent.torrent')
            @lang('common.type')
        </h2>
        <form role="form" method="POST" action="{{ route('staff.types.update', ['id' => $type->id]) }}">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $type->name }}">
                </label>
            </div>
    
            <div class="form-group">
                <label for="name">@lang('common.position')</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $type->position }}">
                </label>
            </div>
    
            <button type="submit" class="btn btn-default">@lang('common.submit')</button>
        </form>
    </div>
@endsection
