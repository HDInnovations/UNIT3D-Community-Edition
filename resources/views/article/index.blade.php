@extends('layout.default')

@section('title')
    <title>{{ __('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('articles.meta-articles') }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('articles.articles') }}
    </li>
@endsection

@section('page', 'page__articles--index')

@section('main')
    @foreach ($articles as $article)
        <article class="article-preview">
            <header class="article-preview__header">
                <h2 class="article-preview__title">
                    <a
                        class="article-preview__link"
                        href="{{ route('articles.show', ['id' => $article->id]) }}"
                    >
                        {{ $article->title }}
                    </a>
                </h2>
                <time
                    class="article-preview__published-date"
                    datetime="{{ $article->created_at }}"
                    title="{{ $article->created_at }}"
                >
                    {{ $article->created_at->diffForHumans() }}
                </time>
                <img
                    class="article-preview__image"
                    src="{{ url($article->image ? 'files/img/'.$article->image : 'img/missing-image.png') }}"
                    alt=""
                >
            </header>
            <p class="article-preview__content">
                @joypixels(preg_replace('#\[[^\]]+\]#', '', Str::limit($article->content, 500, '...'), 150))
            </p>
            <a href="{{ route('articles.show', ['id' => $article->id]) }}" class="article-preview__read-more">
                {{ __('articles.read-more') }}
            </a>
        </article>
    @endforeach
    <div class="text-center">
        {{ $articles->links() }}
    </div>
@endsection
