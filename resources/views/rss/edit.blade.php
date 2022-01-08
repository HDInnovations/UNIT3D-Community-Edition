@extends('layout.default')

@section('title')
    <title>{{ __('rss.edit-private-feed') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('rss.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('rss.rss') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('rss.edit', ['id' => $rss->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('rss.edit') }}</span>
        </a>
    </li>
@endsection


@section('content')
    <div class="container">
        <h1 class="upload-title">{{ __('rss.edit-private-feed') }}</h1>
        <form role="form" method="POST" action="{{ route('rss.update', ['id' => $rss->id]) }}">
            @csrf
            @method('PATCH')
            <div class="block">
                <div class="upload col-md-12">
                    <div class="form-group">
                        <label for="name">{{ __('rss.feed') }} {{ __('rss.name') }}</label>
                        <div>{{ $rss->name }}</div>
                    </div>
                    <div class="form-group">
                        <label for="search">{{ __('torrent.torrent') }} {{ __('torrent.name') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ $rss->object_torrent->search }}">
                    </div>
                    <div class="form-group">
                        <label for="description">{{ __('torrent.torrent') }} {{ __('torrent.description') }}</label>
                        <input type="text" class="form-control" id="description" name="description"
                               value="{{ $rss->object_torrent->description }}">
                    </div>
                    <div class="form-group">
                        <label for="uploader">{{ __('torrent.torrent') }} {{ __('torrent.uploader') }}</label>
                        <input type="text" class="form-control" id="uploader" name="uploader"
                               value="{{ $rss->object_torrent->uploader }}">
                    </div>

                    <div class="form-group">
                        <label for="imdb">IMDB ID</label>
                        <input type="text" class="form-control" id="imdb" name="imdb"
                               value="{{ $rss->object_torrent->imdb }}">
                        <label for="tvdb">TVDB ID</label>
                        <input type="text" class="form-control" id="tvdb" name="tvdb"
                               value="{{ $rss->object_torrent->tvdb }}">
                        <label for="tmdb">TMDB ID</label>
                        <input type="text" class="form-control" id="tmdb" name="tmdb"
                               value="{{ $rss->object_torrent->tmdb }}">
                        <label for="mal">MAL ID</label>
                        <input type="text" class="form-control" id="mal" name="mal"
                               value="{{ $rss->object_torrent->mal }}">
                    </div>

                    <div class="form-group">
                        <label for="category">{{ __('torrent.category') }}</label>
                        <div>
                            @foreach ($categories as $category)
                                <span class="badge-user">
                                    <label class="inline">
                                        @if(is_array($rss->object_torrent->categories) &&
                                            in_array((string)$category->id, $rss->object_torrent->categories, true))
                                            <input type="checkbox" id="{{ $category->name }}" name="categories[]"
                                                   value="{{ $category->id }}"
                                                   class="category" CHECKED> {{ $category->name }}
                                        @else
                                            <input type="checkbox" id="{{ $category->name }}" name="categories[]"
                                                   value="{{ $category->id }}"
                                                   class="category"> {{ $category->name }}
                                        @endif
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('torrent.type') }}</label>
                        <div>
                            @foreach ($types as $type)
                                <span class="badge-user">
                                    <label class="inline">
                                        @if(is_array($rss->object_torrent->types) &&
                                            in_array((string)$type->id, $rss->object_torrent->types, true))
                                            <input type="checkbox" id="{{ $type->name }}" name="types[]"
                                                   value="{{ $type->id }}" class="type"
                                                   CHECKED> {{ $type->name }}
                                        @else
                                            <input type="checkbox" id="{{ $type->name }}" name="types[]"
                                                   value="{{ $type->id }}" class="type">
                                            {{ $type->name }}
                                        @endif
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="resolution">{{ __('torrent.resolution') }}</label>
                        <div>
                            @foreach ($resolutions as $resolution)
                                <span class="badge-user">
                            <label class="inline">
                                @if(is_array($rss->object_torrent->resolutions) && in_array((string)$resolution->id, $rss->object_torrent->resolutions, true))
                                    <input type="checkbox" id="{{ $resolution->name }}" name="resolutions[]"
                                           value="{{ $resolution->id }}" class="resolution" CHECKED>
                                    {{ $resolution->name }}
                                @else
                                    <input type="checkbox" id="{{ $resolution->name }}" name="resolutions[]"
                                           value="{{ $resolution->id }}" class="resolution">
                                    {{ $resolution->name }}
                                @endif
                            </label>
                        </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="genre">{{ __('torrent.genre') }}</label>
                        <div>
                            @foreach ($genres as $genre)
                                <span class="badge-user">
                                    <label class="inline">
                                        @if(is_array($rss->object_torrent->genres) &&
                                            in_array($genre->id, $rss->object_torrent->genres, true))
                                            <input type="checkbox" id="{{ $genre->name }}" name="genres[]"
                                                   value="{{ $genre->id }}"
                                                   class="genre" CHECKED> {{ $genre->name }}
                                        @else
                                            <input type="checkbox" id="{{ $genre->name }}" name="genres[]"
                                                   value="{{ $genre->id }}"
                                                   class="genre"> {{ $genre->name }}
                                        @endif
                                    </label>
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('torrent.discounts') }}</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->freeleech)
                                        <input type="checkbox" id="freeleech" name="freeleech" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-star text-gold"></span>
                                        {{ __('torrent.freeleech') }}
                                    @else
                                        <input type="checkbox" id="freeleech" name="freeleech" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-star text-gold"></span>
                                        {{ __('torrent.freeleech') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->doubleupload)
                                        <input type="checkbox" id="doubleupload" name="doubleupload" value="1" CHECKED>
                                        <span
                                                class="{{ config('other.font-awesome') }} fa-gem text-green"></span>
                                        {{ __('torrent.double-upload') }}
                                    @else
                                        <input type="checkbox" id="doubleupload" name="doubleupload" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-gem text-green"></span>
                                        {{ __('torrent.double-upload') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->featured)
                                        <input type="checkbox" id="featured" name="featured" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span>
                                        {{ __('torrent.featured') }}
                                    @else
                                        <input type="checkbox" id="featured" name="featured" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-certificate text-pink"></span>
                                        {{ __('torrent.featured') }}
                                    @endif
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type">{{ __('torrent.special') }}</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->stream)
                                        <input type="checkbox" id="stream" name="stream" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-play text-red"></span>
                                        {{ __('torrent.stream-optimized') }}
                                    @else
                                        <input type="checkbox" id="stream" name="stream" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-play text-red"></span>
                                        {{ __('torrent.stream-optimized') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->highspeed)
                                        <input type="checkbox" id="highspeed" name="highspeed" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span>
                                        {{ __('common.high-speeds') }}
                                    @else
                                        <input type="checkbox" id="highspeed" name="highspeed" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-tachometer text-red"></span>
                                        {{ __('common.high-speeds') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->sd)
                                        <input type="checkbox" id="sd" name="sd" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span>
                                        {{ __('torrent.sd-content') }}
                                    @else
                                        <input type="checkbox" id="sd" name="sd" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-ticket text-orange"></span>
                                        {{ __('torrent.sd-content') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->internal)
                                        <input type="checkbox" id="internal" name="internal" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-magic"
                                                style="color: #baaf92;"></span>
                                        {{ __('torrent.internal') }}
                                    @else
                                        <input type="checkbox" id="internal" name="internal" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-magic"
                                                style="color: #baaf92;"></span>
                                        {{ __('torrent.internal') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->bookmark)
                                        <input type="checkbox" id="bookmark" name="bookmark" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-bookmark text-blue"></span>
                                        {{ __('torrent.bookmark') }}
                                    @else
                                        <input type="checkbox" id="bookmark" name="bookmark" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-bookmark text-blue"></span>
                                        {{ __('torrent.bookmark') }}
                                    @endif
                                </label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type">{{ __('torrent.health') }}</label>
                        <div>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->alive)
                                        <input type="checkbox" id="alive" name="alive" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-smile text-green"></span>
                                        {{ __('torrent.alive') }}
                                    @else
                                        <input type="checkbox" id="alive" name="alive" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-smile text-green"></span>
                                        {{ __('torrent.alive') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->dying)
                                        <input type="checkbox" id="dying" name="dying" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-meh text-orange"></span>
                                        {{ __('torrent.dying-torrent') }}
                                    @else
                                        <input type="checkbox" id="dying" name="dying" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-meh text-orange"></span>
                                        {{ __('torrent.dying-torrent') }}
                                    @endif
                                </label>
                            </span>
                            <span class="badge-user">
                                <label class="inline">
                                    @if($rss->object_torrent->dead)
                                        <input type="checkbox" id="dead" name="dead" value="1" CHECKED><span
                                                class="{{ config('other.font-awesome') }} fa-frown text-red"></span>
                                        {{ __('torrent.dead-torrent') }}
                                    @else
                                        <input type="checkbox" id="dead" name="dead" value="1"><span
                                                class="{{ config('other.font-awesome') }} fa-frown text-red"></span>
                                        {{ __('torrent.dead-torrent') }}
                                    @endif
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">{{ __('common.edit') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
