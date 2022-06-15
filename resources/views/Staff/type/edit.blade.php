@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.types.index') }}" class="breadcrumb__link">
            {{ __('staff.torrent-types') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>
            {{ __('common.edit') }}
            {{ trans_choice('common.a-an-art',false) }}
            {{ __('torrent.torrent') }}
            {{ __('common.type') }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.types.update', ['id' => $type->id]) }}">
            @method('PATCH')
            @csrf
            <div class="form-group">
                <label for="name">{{ __('common.name') }}</label>
                <label>
                    <input type="text" class="form-control" name="name" value="{{ $type->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="name">{{ __('common.position') }}</label>
                <label>
                    <input type="text" class="form-control" name="position" value="{{ $type->position }}">
                </label>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.submit') }}</button>
        </form>
    </div>
@endsection
