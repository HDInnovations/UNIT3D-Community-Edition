@extends('layout.default')

@section('title')
<title>Polls - {{ Config::get('other.title') }}</title>
@stop

@section('breadcrumb')
<li>
    <a href="{{ route('polls') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">Polls</span>
    </a>
</li>
@stop

@section('content')
<div class="box container">
{{--<span class="badge-user" style="float: right;"><strong>Polls:</strong> 0 | <strong>Total Votes Ever:</strong> 0</span>--}}
<div class="header gradient green">
  <div class="inner_content">
    <h1>Current Poll(s)</h1>
  </div>
</div>
	<div class="forum-categories">
			<div class="forum-category">
				<div class="forum-category-title col-md-12">
				<div class="forum-category-childs">
					@foreach ($polls as $poll)
						<a href="{{ url('/poll/' . $poll->slug) }}" class="forum-category-childs-forum col-md-4">
						<h3 class="text-bold">{{ $poll->title }}</h3>
						<p>Get Your Vote In Now!</p>
						</a>
					@endforeach
				</div>
			</div>
	</div>
</div>
</div>
</div>
@endsection
