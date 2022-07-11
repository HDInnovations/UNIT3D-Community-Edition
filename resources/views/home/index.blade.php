@extends('layout.default')

@section('content')
    <div class="page__home">
        @include('blocks.news')

        @if (!auth()->user()->chat_hidden)
            <div id="vue">
                <script src="{{ mix('js/chat.js') }}" crossorigin="anonymous"></script>
                @include('blocks.chat')
            </div>
        @endif

        @include('blocks.featured')
        @include('blocks.poll')
        @include('blocks.top_torrents')
        @include('blocks.top_uploaders')
        @include('blocks.latest_topics')
        @include('blocks.latest_posts')
        @include('blocks.online')
    </div>
@endsection
