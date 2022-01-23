@extends('layout.default')

@section('title')
    <title>{{ __('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('forum.edit-topic') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_topic', ['id' => $topic->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_edit_topic', ['id' => $topic->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.edit-topic') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2><span>{{ __('forum.edit-topic') }}</span></h2>
            <form role="form" method="POST" action="{{ route('forum_edit_topic', ['id' => $topic->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="forum_name">{{ __('forum.topic-name') }}</label>
                    <label for="name"></label><input id="name" type="text" name="name" maxlength="75"
                                                     class="form-control"
                                                     placeholder="{{ __('forum.topic-title') }}" value="{{ $topic->name }}"
                                                     required>
                </div>

                <div class="form-group">
                    <label for="forum_id">{{ __('forum.forum') }}</label>
                    <label>
                        <select name="forum_id" class="form-control">
                            <option value="{{ $topic->forum_id }}" selected>{{ $topic->forum->name }}
                                ({{ __('forum.current') }})
                            </option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <button type="submit" name="post" value="true" id="post"
                        class="btn btn-primary">{{ __('forum.edit-topic') }}</button>
            </form>
        </div>
    </div>
@endsection
