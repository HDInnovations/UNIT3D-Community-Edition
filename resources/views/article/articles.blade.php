@extends('layout.default')

@section('title')
    <title>@lang('articles.articles') - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('articles.meta-articles')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('articles') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('articles.articles')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="header gradient light_blue">
            <div class="inner_content">
                <h1>@lang('articles.articles')</h1>
            </div>
        </div>
        @foreach ($articles as $article)
            <div class="well">
                <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                   style=" float: right; margin-right: 10px;">
                    @if ( ! is_null($article->image))
                        <img src="{{ url('files/img/' . $article->image) }}" alt="{{ $article->title }}">
                    @else
                        <img src="{{ url('img/missing-image.jpg') }}" alt="{{ $article->title }}">
                    @endif
                </a>

                <h1 class="text-bold" style="display: inline ;">{{ $article->title }}</h1>

                <p class="text-muted">
                    <em>@lang('articles.published-at') {{ $article->created_at->toDayDateTimeString() }}</em>
                </p>

                <p style="margin-top: 20px;">
                    @emojione(preg_replace('#\[[^\]]+\]#', '', Str::limit($article->content), 150))...
                </p>

                <div class="text-center">
                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}" class="btn btn-success">
                        @lang('articles.read-more')
                    </a>
                </div>
            </div>
        @endforeach
        <div class="text-center">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
