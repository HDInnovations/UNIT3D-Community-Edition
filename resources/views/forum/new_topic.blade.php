@extends('layout.default')

@section('title')
    <title>{{ __('forum.create-new-topic') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $forum->name . ' - ' . __('forum.create-new-topic') }}">
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
    <li class="breadcrumb--active">
        {{ __('common.new-adj') }}
    </li>
@endsection

@section('nav-tabs')
    @include('forum.buttons')
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
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
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
