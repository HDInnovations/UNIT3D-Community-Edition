<div class="movie-wrapper">
    <div class="movie-overlay"></div>
    <div class="movie-poster">
        <img style="height: 516px !important;"
             src="{{ (isset($meta) && $meta->cover) ? 'https://images.igdb.com/igdb/image/upload/t_original/'.$meta->cover['image_id'].'.jpg' : 'https://via.placeholder.com/400x600' }}"
             class="img-responsive" id="meta-poster">
    </div>
    <div class="meta-info">
        <div class="tags">
            {{ $torrent->category->name }}
        </div>

        <div class="movie-right">
            @if(isset($meta->involved_companies))
                <div class="badge-user">
                    <a href="{{ $meta->involved_companies[0]['company']['url'] }}" target="_blank">
                        @if(array_key_exists("logo", $meta->involved_companies[0]['company']))
                            <img class="img-responsive"
                                 src="{{ $meta->involved_companies[0]['company']['logo']['image_id'] ? 'https://images.igdb.com/igdb/image/upload/t_logo_med/'.$meta->involved_companies[0]['company']['logo']['image_id'].'.png' : 'https://via.placeholder.com/138x175' }}">
                        @endif
                    </a>
                </div>
            @endif
        </div>

        <div class="movie-backdrop"
             style="background-image: url('{{ (isset($meta) && $meta->artworks) ? 'https://images.igdb.com/igdb/image/upload/t_screenshot_big/'.$meta->artworks[0]['image_id'].'.jpg' : 'https://via.placeholder.com/960x540' }}');"></div>

        <div class="movie-top">
            <h1 class="movie-heading" style="margin-bottom: 0;">
                <span class="text-bright text-bold" style="font-size: 28px;">{{ $meta->name ?? 'No Meta Found' }}</span>
                @if(isset($meta->first_release_date))
                    <span style="font-size: 28px;"> ({{ substr($meta->first_release_date, 0, 4) ?? '' }})</span>
                @endif
            </h1>

            <div class="movie-overview">
                {{ isset($meta->summary) ? Str::limit($meta->summary, $limit = 600, $end = '...') : '' }}
            </div>
        </div>

        <div class="movie-bottom">
            <div class="movie-details">
                @if (isset($meta) && $meta->url && $torrent->igdb !== 0 && $torrent->igdb !== null)
                    <span class="badge-user text-bold">
                        <a href="{{ $meta->url }}" title="IGDB" target="_blank">
                            <i class="{{ config('other.font-awesome') }} fa-gamepad"></i> IGDB: {{ $torrent->igdb }}
                        </a>
                    </span>
                @endif

                @if (isset($trailer))
                    <span style="cursor: pointer;" class="badge-user text-bold show-trailer">
                        <a class="text-pink" title="{{ __('torrent.trailer') }}">{{ __('torrent.trailer') }}
                            <i class="{{ config('other.font-awesome') }} fa-external-link"></i>
                        </a>
                    </span>
                @endif

                <br>
                @if (isset($meta->genres))
                    @foreach ($meta->genres as $genre)
                        <span class="badge-user text-bold text-green">
                            <i class="{{ config('other.font-awesome') }} fa-theater-masks"></i> {{ $genre['name'] }}
                        </span>
                    @endforeach
                @endif

                <br>
                @if ($torrent->keywords)
                    @foreach ($torrent->keywords as $keyword)
                        <span class="badge-user text-bold text-green">
                            <a href="{{ route('torrents') }}?keywords={{ $keyword->name }}">
                                <i class="{{ config('other.font-awesome') }} fa-tag"></i> {{ $keyword->name }}
                            </a>
                        </span>
                    @endforeach
                @endif
            </div>

            <div class="movie-details">
                <span class="badge-user text-bold text-gold">{{ __('torrent.rating') }}:
                    <span class="movie-rating-stars">
                        <i class="{{ config('other.font-awesome') }} fa-star"></i>
                    </span>
                    {{ round($meta->rating ?? 0) }}/100 ({{ $meta->rating_count ?? 0 }} {{ __('torrent.votes') }})
                </span>
            </div>

            <span class="badge-user text-bold">
                <i class="fab fa-xbox"></i> Platforms:
            </span>
            <div class="cast-list">
                @if (isset($platforms))
                    @foreach ($platforms as $platform)
                        <div class="cast-item" style="max-width: 80px;">
                            <img class="img-responsive"
                                 src="{{ $platform->image_id ? 'https://images.igdb.com/igdb/image/upload/t_logo_med/'.$platform->image_id.'.png' : 'https://via.placeholder.com/138x175' }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
