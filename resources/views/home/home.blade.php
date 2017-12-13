@extends('layout.default')

@section('content')
<div class="container-fluid">
  @include('blocks.news')
  @include('blocks.chat')
  @include('blocks.featured')
  @include('blocks.top_torrents')
  @include('blocks.latest_topics')
  @include('blocks.latest_posts')
  @include('blocks.online')
</div>

</div>
</div>
</div>

@stop
