@extends('layout.default')

@section('title')
    <title>{{ __('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . __('forum.create-new-topic') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('forums.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.forums') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forums.show', ['id' => $forum->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('forum_new_topic_form', ['id' => $forum->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('forum.create-new-topic') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="forum box container">
        <div class="col-md-12">
            <h2><span>{{ __('forum.create-new-topic') }}</span><span id="thread-title">{{ $title }}</span></h2>
            <form role="form" method="POST" action="{{ route('forum_new_topic', ['id' => $forum->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="input-thread-title"></label><input id="input-thread-title" type="text" name="title"
                                                                   maxlength="75" class="form-control"
                                                                   placeholder="{{ __('forum.topic-title') }}">
                </div>

                <div class="form-group">
                    <label for="new-thread-content"></label>
                    <textarea id="new-thread-content" name="content" cols="30" rows="10"
                              class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('forum.send-new-topic') }}</button>
            </form>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        const title = '{{ $title }}'
        if (title.length != 0) {
          $('#thread-title').text(': ' + title)
        }

        $('#input-thread-title').on('input', function () {
          $('#thread-title').text(': ' + $('#input-thread-title').val())
        })

        $('#new-thread-content').wysibb({})
      })

    </script>
@endsection
