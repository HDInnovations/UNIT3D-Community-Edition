@extends('layout.default')

@section('title')
    <title>{{ __('common.edit') }} {{ __('forum.post') }} - {{ $topic->name }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . __('forum.edit-post') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('forums.index') }}" class="breadcrumb__link">
            {{ __('forum.forums') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" class="breadcrumb__link">
            {{ $forum->name }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" class="breadcrumb__link">
            {{ $topic->name }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.edit') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2>{{ __('common.edit') }} {{ __('forum.post') }} {{ strtolower(__('forum.in')) }}
                : {{ $forum->name }}</h2>
            <form role="form" method="POST"
                  action="{{ route('forum_post_edit', ['id' => $topic->id, 'postId' => $post->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="content"></label>
                    <textarea id="editor" name="content" cols="30" rows="10"
                              class="form-control">{{ $post->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
