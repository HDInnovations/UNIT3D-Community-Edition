@extends('layout.default')

@section('title')
    <title>MediaHub - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MediaHub">
@endsection

@section('breadcrumb')
    <li class="active">
        <a href="{{ route('mediahub.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">MediaHub</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <div class="header mediahub text-center">
                <div class="mediahub_content">
                    <div class="content">

                        <h1 style=" height: 150px; font-family: Shrikhand, cursive; font-size: 7em; font-weight: 400; margin: 0;">MediaHub</h1>
                        <h3>Please Select A Hub</h3>

                        <div class="blocks text-center" style=" justify-content: center;">
                            {{--TV Shows--}}
                            <a href="{{ route('mediahub.shows.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>TV Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $tv }} Shows</h2>
                                </div>
                            </a>

                            {{--Movies--}}
                            <a href="{{ route('mediahub.movies.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Movies Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $movies }} Movies</h2>
                                </div>
                            </a>

                            {{--Collections--}}
                            <a href="{{ route('mediahub.collections.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Collections Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $collections }} Collections</h2>
                                </div>
                            </a>

                            {{--People--}}
                            <a href="{{ route('mediahub.persons.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Persons Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $persons }} People</h2>
                                </div>
                            </a>

                            {{--Genres--}}
                            <a href="{{ route('mediahub.genres.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Genres Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $genres }} Genres</h2>
                                </div>
                            </a>

                            {{--Networks--}}
                            <a href="{{ route('mediahub.networks.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Networks Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $networks }} Networks</h2>
                                </div>
                            </a>

                            {{--Companies--}}
                            <a href="{{ route('mediahub.companies.index') }}" class="">
                                <div class="movie media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                                    <h2>Companies Hub</h2>
                                    <span style="background-color: #01d277;"></span>
                                    <h2 style="font-size: 12px;">{{ $companies }} Companies</h2>
                                </div>
                            </a>
                        </div>
                        <br>
                        <h6 style="font-size: 10px;">
                            <i>This product uses the TMDb API but is not endorsed or certified by TMDb.</i>
                        </h6>
                        <img src="/img/tmdb_long.svg" style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection