@extends('layout.default')

@section('title')
    <title>{{ $company->name }} Company - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $company->name }} Network">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
    <li>
        <a href="{{ route('mediahub.networks.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Companies</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('mediahub.networks.show', ['id' => $company->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ $company->name }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div style="width: 100% !important; display: table !important;">
                <div class="header mediahub" style="width: 100% !important; display: table-cell !important;">
                    <h1 class="text-center" style=" height: 100px; font-family: Shrikhand, cursive; font-size: 4em; font-weight: 400; margin: 0;">
                        {{ $company->name }}
                    </h1>
                    <h2 class="text-center" style="margin: 0;">{{ $company->tv->count() }} Shows</h2>
                    @foreach($company->tv as $show)
                        <div class="col-md-12">
                            <div class="card is-torrent">
                                <div class="card_head">
                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $show->episode_run_time }}
                                </span>
                                </div>
                                <div class="card_body">
                                    <div class="body_poster">
                                        <img src="{{ $show->poster }}" class="show-poster">
                                    </div>
                                    <div class="body_description">
                                        <h3 class="description_title">
                                            <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">{{ $show->name }}
                                                <span class="text-bold text-pink"> ({{ $show->first_air_date }})</span>
                                            </a>
                                        </h3>
                                        @if ($show->genres)
                                            @foreach ($show->genres as $genre)
                                                <span class="genre-label">{{ $genre->name }}</span>
                                            @endforeach
                                        @endif
                                        <p class="description_plot">
                                            {{ $show->overview }}
                                        </p>
                                    </div>
                                </div>
                                <div class="card_footer">
                                    <div style="float: left;">

                                    </div>
                            </div>
                        </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection