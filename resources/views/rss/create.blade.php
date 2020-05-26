@extends('layout.default')

@section('title')
    <title>@lang('rss.create-private-feed') - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('rss.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('rss.rss')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('rss.create') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('rss.create')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h1 class="upload-title">@lang('rss.create-private-feed')</h1>
        <form role="form" method="POST" action="{{ route('rss.store') }}">
            @csrf
            <div class="block">
                <div class="upload col-md-12">
                    <div class="form-group">
                        <label for="name">@lang('rss.feed') @lang('rss.name')</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="search">@lang('torrent.torrent') @lang('torrent.name')</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="@lang('torrent.torrent') @lang('torrent.name')">
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('torrent.torrent') @lang('torrent.description')</label>
                        <input type="text" class="form-control" id="description" name="description"
                            placeholder="@lang('torrent.torrent') @lang('torrent.description')">
                    </div>
                    <div class="form-group">
                        <label for="uploader">@lang('torrent.torrent') @lang('torrent.uploader')</label>
                        <input type="text" class="form-control" id="uploader" name="uploader"
                            placeholder="@lang('torrent.torrent') @lang('torrent.uploader')">
                    </div>
    
                    <div class="form-group">
                        <label for="imdb">IMDB ID</label>
                        <input type="text" class="form-control" id="imdb" name="imdb" placeholder="IMDB #">
                        <label for="tvdb">TVDB ID</label><input type="text" class="form-control" id="tvdb" name="tvdb"
                            placeholder="TVDB #">
                        <label for="tmdb">TMDB ID</label><input type="text" class="form-control" id="tmdb" name="tmdb"
                            placeholder="TMDB #">
                        <label for="mal">MAL ID</label><input type="text" class="form-control" id="mal" name="mal"
                            placeholder="MAL #">
                    </div>
    
                    <div class="form-group">
                        <label for="category">@lang('torrent.category')</label>
                        <div>
                            @foreach ($torrent_repository->categories() as $id => $category)
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="{{ $category }}" name="categories[]" value="{{ $id }}"
                                            class="category"> {{ $category }}
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">@lang('torrent.type')</label>
                        <div>
                            @foreach ($torrent_repository->types() as $id => $type)
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="{{ $type }}" name="types[]" value="{{ $id }}" class="type">
                                        {{ $type->name }}
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="genre">@lang('torrent.genre')</label>
                        <div>
                            @foreach ($torrent_repository->tags() as $id => $genre)
                                <span class="badge-user">
                                    <label class="inline">
                                        <input type="checkbox" id="{{ $genre }}" name="genres[]" value="{{ $genre }}"
                                            class="genre"> {{ $genre }}
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">@lang('torrent.discounts')</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="freeleech" name="freeleech" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-star text-gold"></span>
                                    @lang('torrent.freeleech')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="doubleupload" name="doubleupload" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-gem text-green"></span>
                                    @lang('torrent.double-upload')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="featured" name="featured" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span>
                                    @lang('torrent.featured')
                                </label>
                            </span>
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="type">@lang('torrent.special')</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="stream" name="stream" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-play text-red"></span>
                                    @lang('torrent.stream-optimized')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="highspeed" name="highspeed" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span>
                                    @lang('common.high-speeds')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="sd" name="sd" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span>
                                    @lang('torrent.sd-content')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="internal" name="internal" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-magic" style="color: #baaf92;"></span>
                                    @lang('torrent.internal')
                                </label>
                            </span>
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="type">@lang('torrent.health')</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="alive" name="alive" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-smile text-green"></span>
                                    @lang('torrent.alive')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="dying" name="dying" value="1"> <span
                                        class="{{ config('other.font-awesome') }} fa-meh text-orange"></span>
                                    @lang('torrent.dying-torrent')
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    <input type="checkbox" id="dead" name="dead" value="0"> <span
                                        class="{{ config('other.font-awesome') }} fa-frown text-red"></span>
                                    @lang('torrent.dead-torrent')
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">@lang('common.create')</button>
                </div>
            </div>
        </form>
    </div>
@endsection
