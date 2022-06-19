@extends('layout.default')

@section('title')
    <title>{{ $collection->name }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ $collection->name }}">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.collections.index') }}" class="breadcrumb__link">
            {{ __('mediahub.collections') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $collection->name }}
    </li>
@endsection

@section('content')
    <div class="torrent box container single">
        <div class="movie-wrapper">
            <div class="movie-overlay"></div>

            <div class="movie-poster">
                <img src="{{ $collection->poster ? tmdb_image('poster_big', $collection->poster) : 'https://via.placeholder.com/400x600' }}"
                     class="img-responsive" id="meta-poster">
            </div>

            <div class="meta-info">
                <div class="tags">
                    {{ __('mediahub.collections') }}
                </div>

                <div class="movie-backdrop"
                     style="background-image: url('{{ $collection->backdrop ? tmdb_image('back_big', $collection->backdrop) : 'https://via.placeholder.com/960x540' }}');"></div>

                <div class="movie-top">
                    <h1 class="movie-heading">
                        <span class="text-bold">{{ $collection->name }}</span>
                    </h1>

                    <div class="movie-overview">
                        {{ $collection->overview }}
                    </div>
                </div>
                <div class="movie-bottom">
                    <a href="{{ route('torrents', ['collectionId' => $collection->id]) }}" role="button"
                       class="btn btn-sm btn-labeled btn-success">
                    <span class='btn-label'>
                        <i class='{{ config('other.font-awesome') }} fa-eye'></i> Collection Torrents List
                    </span>
                    </a>
                </div>
            </div>
        </div>

        <br>

        <div class="panel panel-chat shoutbox">
            <div class="panel-heading">
                <h4><i class="{{ config("other.font-awesome") }} fa-film"></i> Movies</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <tbody>
                    <tr>
                        <td>
                            <section class="recommendations">
                                @foreach($collection->movie->sortBy('release_date') as $movie)
                                    <div class="item mini backdrop mini_card col-md-3">
                                        <div class="image_content">
                                            @php
                                                $torrent_temp = App\Models\Torrent::where('tmdb', '=', $movie->id)
                                                ->whereIn('category_id', function ($query) {
                                                $query->select('id')->from('categories')->where('movie_meta', '=', true);
                                                })->first()
                                            @endphp
                                            <a href="{{ route('torrents.similar', ['category_id' => $torrent_temp->category_id, 'tmdb' => $movie->id]) }}">
                                                <div>
                                                    <img class="backdrop"
                                                         src="{{ tmdb_image('poster_mid', $movie->poster) }}">
                                                </div>
                                                <div style=" margin-top: 8px;">
                                                    <span class="badge-extra"><i
                                                                class="fas fa-calendar text-purple"></i> {{ __('common.year') }}: {{ substr($movie->release_date, 0, 4) }}</span>
                                                    <span class="badge-extra"><i class="fas fa-star text-gold"></i> {{ __('torrent.rating') }}: {{ $movie->vote_average }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </section>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="torrent box container" id="comments">
        <div class="col-md-12 col-sm-12">
            <livewire:comments :model="$collection"/>
        </div>
    </div>
@endsection
