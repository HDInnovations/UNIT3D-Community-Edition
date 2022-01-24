@extends('layout.default')

@section('title')
    <title>{{ $article->title }} - {{ __('articles.articles') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ substr(strip_tags($article->content), 0, 200) }}...">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('articles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('articles.articles') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('articles.show', ['id' => $article->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $article->title }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="box container">
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

        <p style="margin-top: 20px;">@joypixels($article->getContentHtml())</p>
    </div>

    <div class="box container">
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-chat shoutbox">
                <div class="panel-heading">
                    <h4>
                        <i class="{{ config('other.font-awesome') }} fa-comment"></i> {{ __('common.comments') }}
                    </h4>
                </div>
                <div class="panel-body no-padding">
                    <livewire:comments :model="$article"/>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {

        $('#content').wysibb({})
      })
    </script>
@endsection
