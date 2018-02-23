@extends('layout.default')

@section('breadcrumb')
<li>
	<a href="{{ route('page', ['slug' => $page->slug, 'id' => $page->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
		<span itemprop="title" class="l-breadcrumb-item-link-title">{{ $page->name }}</span>
	</a>
</li>
@endsection

@section('content')
<div class="container box">
	<div class="col-md-12 page">
		<div class="header gradient silver">
			<div class="inner_content">
				<div class="page-title"><h1>{{ $page->name }}</h1></div>
			</div>
		</div>
		<article class="page-content">
			@emojione($page->getContentHtml())
		</article>
	</div>
</div>
@endsection
