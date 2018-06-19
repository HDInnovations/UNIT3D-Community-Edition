@extends('layout.default')

@section('title')
    <title>{{ trans('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ trans('forum.edit-topic') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_edit_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.edit-topic') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2><span>{{ trans('forum.edit-topic') }}</span></h2>
            <form role="form" method="POST"
                  action="{{ route('forum_edit_topic',['slug' => $topic->slug, 'id' => $topic->id]) }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="forum_name">{{ trans('forum.topic-name') }}</label>
                    <input id="name" type="text" name="name" maxlength="75" class="form-control"
                           placeholder="{{ trans('forum.topic-title') }}" value="{{ $topic->name }}" required>
                </div>

                <div class="form-group">
                    <label for="forum_id">{{ trans('forum.forum') }}</label>
                    <select name="forum_id" class="form-control">
                        <option value="{{ $topic->forum_id }}" selected>{{ $topic->forum->name  }}
                            ({{ trans('forum.current') }})
                        </option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" name="post" value="true" id="post"
                        class="btn btn-primary">{{ trans('forum.edit-topic') }}</button>
            </form>
        </div>
    </div>
@endsection
