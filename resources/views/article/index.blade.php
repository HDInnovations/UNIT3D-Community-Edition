@extends('layout.default')

@section('title')
    <title>{{ __('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('articles.meta-articles') }}">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('articles.articles') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        @foreach ($articles as $article)
            <div class="well">
                <a href="{{ route('articles.show', ['id' => $article->id]) }}"
                   style=" float: right; margin-right: 10px;">
                    @if ( ! is_null($article->image))
                        <img src="{{ url('files/img/' . $article->image) }}" alt="{{ $article->title }}">
                    @else
                        <img src="{{ url('img/missing-image.png') }}" alt="{{ $article->title }}">
                    @endif
                </a>

                <h1 class="text-bold" style="display: inline ;">{{ $article->title }}</h1>

                <p class="text-muted">
                    <em>{{ __('articles.published-at') }} {{ $article->created_at->toDayDateTimeString() }}</em>
                </p>

                <p style="margin-top: 20px;">
                    @joypixels(preg_replace('#\[[^\]]+\]#', '', Str::limit($article->content), 150))...
                </p>

                <div class="text-center">
                    <a href="{{ route('articles.show', ['id' => $article->id]) }}" class="btn btn-success">
                        {{ __('articles.read-more') }}
                    </a>
                </div>
            </div>
        @endforeach
        <div class="text-center">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
