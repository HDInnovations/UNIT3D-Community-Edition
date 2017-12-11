@extends('layout.default')

@section('title')
<title>Forums - {{ Config::get('other.title') }}</title>
@stop

@section('meta')
<meta name="description" content="{{ 'Forum de partage et d\'échange de ' . Config::get('other.title') . '. Téléchargez vos films et séries préférer en torrent. Rejoignez la communauté.' }}">
@stop


@section('breadcrumb')
<li class="active">
  <a href="{{ route('forum_index') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Forums</span>
  </a>
</li>
@stop

@section('content')
<div class="box container">
<span class="badge-user" style="float: right;"><strong>Forums:</strong> {{ $num_forums }} | <strong>Topics:</strong> {{ $num_topics }} | <strong>Posts:</strong> {{ $num_posts }}</span>
{{ Form::open(array('route' => 'forum_search')) }}
<input type="text" name="name" id="name" placeholder="Topic Name Quick Search" class="form-control">
{{ Form::close() }}
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
              <td>NAME</td>
              <td>POSTS</td>
              <td>TOPICS</td>
              <td>LATEST</td>
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
              <span>Last Post By <i class="fa fa-user"></i> <a href="{{ route('profil', ['username' => $categoryChild->last_post_user_username, 'id' => $categoryChild->last_post_user_id]) }}"> {{ $categoryChild->last_post_user_username }}</a></span>
              <br>
              <span>In <i class="fa fa-chevron-right"></i><a href="{{ route('forum_topic', array('slug' => $categoryChild->last_topic_slug, 'id' => $categoryChild->last_topic_id)) }}"> {{ $categoryChild->last_topic_name }}</a></span>
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
@stop
