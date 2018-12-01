@extends('layout.default')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                @include('blocks.news')
                @includeWhen(!$user->chat_hidden, 'blocks.chat')
                @include('blocks.featured')
                @include('blocks.top_torrents')
                @include('blocks.top_uploaders')
            </div>

            <div class="col-md-3">
                @include('blocks.poll')
                @include('blocks.latest_topics')
                @include('blocks.latest_posts')
                @include('blocks.online')
            </div>
        </div>
    </div>
@endsection
