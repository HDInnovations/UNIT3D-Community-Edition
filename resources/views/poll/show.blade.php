@extends('layout.default')

@section('title')
<title>Poll - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Polls</span>
    </a>
</li>
<li>
    <a href="{{ route('poll', ['slug' => $poll->slug]) }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Poll</span>
    </a>
</li>
@stop

@section('content')
<div class="box container">
	<div class="page-title"><h1>{{ $poll->title }}</h1></div>
	<hr>
	<div class="forum-categories">
			<div class="forum-category">
				<div class="forum-category-title col-md-12">
				<div class="forum-category-childs">
					@include('poll.forms.vote')

					@if($poll->multiple_choice)
						<span class="badge-user text-bold text-red">This is a multiple choice poll. Select as many answers as you like.</span>
					@endif
					@if($poll->ip_checking)
						<span class="badge-user text-bold text-red">This poll has duplicate vote checking. You can only vote once.</span>
					@endif
				</div>
			</div>
	</div>
</div>
</div>
</div>
@endsection
