@extends('layout.default')

@section('title')
    <title>Articles - {{ __('staff.staff-dashboard') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('staff.articles.index') }}" class="breadcrumb__link">
            {{ __('staff.articles') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>{{ __('common.article') }} {{ __('common.edit') }}</h2>
        <form role="form" method="POST" enctype="multipart/form-data"
              action="{{ route('staff.articles.update', ['id' => $article->id]) }}">
            @csrf
            <div class="form-group">
                <label for="title">{{ __('common.title') }}</label>
                <label>
                    <input type="text" class="form-control" name="title" value="{{ $article->title }}" required>
                </label>
            </div>

            <div class="form-group">
                <label for="image">{{ __('common.image') }}</label>
                <input type="file" name="image">
            </div>

            <div class="form-group">
                <label for="content">{{ __('staff.article-content') }}</label>
                <textarea name="content" id="editor" cols="30" rows="10"
                          class="form-control">{{ $article->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-default">{{ __('common.save') }}</button>
        </form>
    </div>
@endsection
