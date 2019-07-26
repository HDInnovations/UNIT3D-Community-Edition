@if (isset($meta) && $meta->recommendations)
    <div class="torrent box container">
        <section class="recommendations">
            <div class="text-center">
                <h2><u>Recommendations</u></h2>
            </div>
            <div class="scroller">
                @foreach($meta->recommendations['results'] as $recommendation)
                    <div class="item mini backdrop mini_card">
                        <p class="tv flex">
                            @if ($recommendation['exists'])
                                <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $recommendation['id']]) }}">
                            @else
                                <a href="{{ route('add_request_form', ['title' => isset($recommendation['title']) ? $recommendation['title'] : $recommendation['name'], 'imdb' => 0, 'tmdb' => $recommendation['id']]) }}">
                            @endif
                            </a>
                        </p>
                        <div class="image_content">
                            @if ($recommendation['exists'])
                                <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $recommendation['id']]) }}">
                            @else
                                <a href="{{ route('add_request_form', ['title' => isset($recommendation['title']) ? $recommendation['title'] : $recommendation['name'], 'imdb' => 0, 'tmdb' => $recommendation['id']]) }}">
                            @endif
                                <div>
                                    @if(isset($recommendation['poster_path']))
                                        <img class="backdrop" src="https://image.tmdb.org/t/p/w440_and_h660_face{{ $recommendation['poster_path'] }}">
                                    @else
                                        <div class="no_image_holder w300_and_h450 backdrop"></div>
                                    @endif
                                </div>
                                <div style=" margin-top: 8px">
                                    <span class="badge-extra">
                                        <i class="fas fa-clock"></i> @lang('common.year'):
                                        @if(isset($recommendation['release_date']))
                                            {{ substr($recommendation['release_date'], 0, 4) }}
                                        @elseif(isset($recommendation['first_air_date']))
                                            {{ substr($recommendation['first_air_date'], 0, 4) }}
                                        @else
                                            @lang('common.unknown')
                                        @endif
                                    </span>
                                    <span class="badge-extra">
                                        <i class="fas fa-star text-gold"></i> Rating: {{ $recommendation['vote_average'] }}
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endif