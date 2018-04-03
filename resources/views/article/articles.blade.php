@extends('layout.default')

@section('title')
<title>{{ trans('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
<meta name="description" content="{{ trans('articles.meta-articles') }}">
@endsection

@section('breadcrumb')
<li>
    <a href="{{ route('articles') }}" itemprop="url" class="l-breadcrumb-item-link">
        <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('articles.articles') }}</span>
    </a>
</li>
@endsection

@section('content')
<div class="container box">
  <div class="header gradient light_blue">
    <div class="inner_content">
      <h1>{{ trans('articles.articles') }}</h1>
    </div>
  </div>
    <section class="articles">
        @foreach($articles as $a)
            <article class="article col-md-12">
                <div class="row">
                    <a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}" class="article-thumb col-md-2">
                        @if( ! is_null($a->image))
                            <img src="{{ url('files/img/' . $a->image) }}" class="article-thumb-img" alt="{{ $a->title }}">
                        @else
                            <img src="{{ url('img/missing-image.jpg') }}" class="article-thumb-img" alt="{{ $a->title }}">
                        @endif
                    </a>

                    <div class="col-md-8 article-title">
                        <h2><a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}">{{ $a->title }}</a></h2>
                    </div>

                    <div class="col-md-8 article-info">
                        <span>{{ trans('articles.published-at') }}</span>
                        <time datetime="{{ date(DATE_W3C, $a->created_at->getTimestamp()) }}">{{ date('d M Y', $a->created_at->getTimestamp()) }}</time>
                    </div>

                    <div class="col-md-8 article-content">
                        @emojione(preg_replace('#\[[^\]]+\]#', '', str_limit($a->content), 150))...
                    </div>

                    <div class="col-md-12 article-readmore">
                    <center>
                        <a href="{{ route('article', ['slug' => $a->slug, 'id' => $a->id]) }}" class="btn btn-success">{{ trans('articles.read-more') }}</a>
                    </center>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <div class="col-md-12 home-pagination">
        {{ $articles->links() }}
    </div>
  </div>
</div>
@endsection
