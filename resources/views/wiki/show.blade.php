@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('wikis.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Wikis</span>
        </a>
    </li>
    <li>
        <a href="{{ route('wikis.show', ['id' => $wiki->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $wiki->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="col-md-12 page">
            <div class="header gradient silver">
                <div class="inner_content">
                    <div class="page-title">
                        <h1>{{ $wiki->name }}</h1>
                    </div>
                </div>
            </div>
            <article class="page-content bbcode-rendered">
                @joypixels($wiki->getContentHtml())
            </article>
        </div>
    </div>
@endsection
