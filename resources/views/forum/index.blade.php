@extends('layout.default')

@section('title')
<title>{{ trans('forum.forums') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ config('other.title') }} - {{ trans('forum.forums') }}">
@endsection


@section('breadcrumb')
<li class="active">
  <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('forum.forums') }}</span>
  </a>
</li>
@endsection

@section('content')
<div class="box container">
<span class="badge-user" style="float: right;"><strong>{{ trans('forum.forums') }}:</strong> {{ $num_forums }} | <strong>{{ trans('forum.topics') }}:</strong> {{ $num_topics }} | <strong>{{ trans('forum.posts') }}:</strong> {{ $num_posts }}</span>
<form role="form" method="POST" action="{{ route('forum_search') }}">
{{ csrf_field() }}
<input type="text" name="name" id="name" placeholder="{{ trans('forum.topic-quick-search') }}" class="form-control">
</form>
	<div class="forum-categories">
		@foreach($categories as $category)
			@if($category->getPermission() != null && $category->getPermission()->show_forum == true && $category->getForumsInCategory()->count() > 0)
        <div class="header gradient teal">
          <div class="inner_content">
            <h1>{{ $category->name }}</h1>
          </div>
        </div>

        <table class="table table-bordered table-hover">
          <thead class="head">
            <tr>
              <td> </td>
              <td>{{ strtoupper(trans('forum.name')) }}</td>
              <td>{{ strtoupper(trans('forum.posts')) }}</td>
              <td>{{ strtoupper(trans('forum.topics')) }}</td>
              <td>{{ strtoupper(trans('forum.latest')) }}</td>
            </tr>
		       </thead>
				<tbody>
					@foreach($category->getForumsInCategory() as $categoryChild)
          <tr>
            <td><img src="{{ url('img/forum.png') }}"></td>
						<td>
            <span><h4><a href="{{ route('forum_display', ['slug' => $categoryChild->slug, 'id' => $categoryChild->id]) }}"><span class="text-bold">{{ $categoryChild->name }}</span></a><h4></span>
						<span class="">{{ $categoryChild->description }}</span>
            </td>
            <td>{{ $categoryChild->num_post }}</td>
            <td>{{ $categoryChild->num_topic }}</td>
            <td>
              <span>{{ trans('forum.last-message') }} - {{ strtolower(trans('forum.author')) }} <i class="fa fa-user"></i> <a href="{{ route('profile', ['username' => $categoryChild->last_post_user_username, 'id' => $categoryChild->last_post_user_id]) }}"> {{ $categoryChild->last_post_user_username }}</a></span>
              <br>
              <span>{{ trans('forum.topic') }} <i class="fa fa-chevron-right"></i><a href="{{ route('forum_topic', array('slug' => $categoryChild->last_topic_slug, 'id' => $categoryChild->last_topic_id)) }}"> {{ $categoryChild->last_topic_name }}</a></span>
              <br>
              <span><i class="fa fa-clock-o"></i> {{ $categoryChild->updated_at->diffForHumans() }}</span>
            </td>
          </tr>
					@endforeach
				</tbody>
      </table>
			@endif
		@endforeach
	</div>
</div>
@endsection
