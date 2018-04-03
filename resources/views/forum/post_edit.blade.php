@extends('layout.default')

@section('title')
<title>{{ trans('common.edit') }} {{ trans('forum.post') }} - {{ $topic->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ $forum->name . ' - ' . trans('forum.edit-post') }}">
@endsection

@section('stylesheets')
<link rel="stylesheet" href="{{ url('files/wysibb/theme/default/wbbtheme.css') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
    </a>
</li>
<li>
    <a href="{{ route('forum_category', array('slug' => $category->slug, 'id' => $category->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $category->name }}</span>
    </a>
</li>
<li>
    <a href="{{ route('forum_display', array('slug' => $forum->slug, 'id' => $forum->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $forum->name }}</span>
    </a>
</li>
<li>
    <a href="{{ route('forum_topic', array('slug' => $topic->slug, 'id' => $topic->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $topic->name }}</span>
    </a>
</li>
<li>
    <a href="{{ route('forum_post_edit', array('slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id)) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('common.edit') }} {{ trans('forum.post') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="forum box container">
	@if(isset($parsedContent))
		<div class="preview col-md-12">
			{{ $parsedContent }}
		</div><hr>
	@endif

	<div class="col-md-12">
		<h2>{{ trans('common.edit') }} {{ trans('forum.post') }} {{ strtolower(trans('forum.in')) }}: {{ $forum->name }}</h2>
        <form role="form" method="POST" action="{{ route('forum_post_edit',['slug' => $topic->slug, 'id' => $topic->id, 'postId' => $post->id]) }}">
        {{ csrf_field() }}
			<div class="form-group">
				<textarea id="content" name="content" cols="30" rows="10" class="form-control">{{ $post->content }}</textarea>
			</div>
			<button type="submit" name="post" value="true" class="btn btn-primary">{{ trans('common.submit') }}</button>
			<button type="submit" name="preview" value="true" class="btn btn-default">{{ trans('common.preview') }}</button>
		</form>
	</div>
</div>
@endsection

@section('javascripts')
<script type="text/javascript" src="{{ url('files/wysibb/jquery.wysibb.js') }}"></script>

<script>
$(document).ready(function() {
    $("#content").wysibb();
});
</script>
@endsection
