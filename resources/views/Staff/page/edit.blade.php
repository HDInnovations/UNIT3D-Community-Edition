@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.pages.index') }}" class="breadcrumb__link">
            {{ __('staff.pages') }}
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
            {{ __('staff.page') }}
            {{ $page->name }}
        </h2>
        <form role="form" method="POST" action="{{ route('staff.pages.update', ['id' => $page->id]) }}">
            @csrf
            <div class="form-group">
                <label for="name">{{ __('staff.page') }} {{ __('common.name') }}</label>
                <label>
                    <input type="text" name="name" class="form-control" value="{{ $page->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="content">{{ __('common.content') }}</label>
                <textarea name="content" id="editor" cols="30" rows="10"
                          class="form-control">{{ $page->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.save') }}</button>
        </form>
    </div>
@endsection
