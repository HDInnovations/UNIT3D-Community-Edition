@extends('layout.default')

@section('content')
<div class="container-fluid">
  @include('blocks.news')

  @if(!auth()->user()->chat_hidden)
    @include('blocks.chat')
  @endif

  @include('blocks.featured')
  @include('blocks.top_torrents')
  @include('blocks.latest_topics')
  @include('blocks.latest_posts')
  @include('blocks.online')
</div>
@endsection
