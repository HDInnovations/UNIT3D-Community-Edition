@extends('layout.default')

@section('title')
    <title>{{ trans('common.edit') }} {{ trans('forum.post') }} - {{ $topic->name }}
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . trans('forum.edit-post') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_category', ['slug' => $category->slug, 'id' => $category->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_display', ['slug' => $forum->slug, 'id' => $forum->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_post_edit_form', ['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]) }}"
           itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ trans('common.edit') }} {{ trans('forum.post') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2>{{ trans('common.edit') }} {{ trans('forum.post') }} {{ strtolower(trans('forum.in')) }}
                : {{ $forum->name }}</h2>
            <form role="form" method="POST"
                  action="{{ route('forum_post_edit',['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]) }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea id="content" name="content" cols="30" rows="10"
                              class="form-control">{{ $post->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
      $(document).ready(function () {
        $('#content').wysibb()
        emoji.textcomplete()
      })
    </script>
@endsection
