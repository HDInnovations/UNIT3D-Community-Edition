<div class="panel-body" style="padding: 5px;">
    <section class="recommendations" style="max-height: 330px !important;">
        <div class="scroller" style="padding-bottom: 10px;">
            @if(isset($meta->recommendations))
            @forelse($meta->recommendations as $recommendation)
                <div class="item mini backdrop mini_card">
                    <div class="image_content">
                        <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $recommendation->recommendation_movie_id ?? $recommendation->recommendation_tv_id]) }}">
                            <div>
                                @if(isset($recommendation->poster))
                                    <img class="backdrop"
                                         src="{{ tmdb_image('poster_big', $recommendation->poster) }}">
                                @else
                                    <div class="no_image_holder w300_and_h450 backdrop"></div>
                                @endif
                            </div>
                            <div style=" margin-top: 8px">
		    									    <span class="badge-extra">
		    										    <i class="fas fa-clock"></i> {{ __('common.year') }}:
		    										    @if(isset($recommendation->release_date))
                                                            {{ substr($recommendation->release_date, 0, 4) }}
                                                        @elseif(isset($recommendation->first_air_date))
                                                            {{ substr($recommendation->first_air_date, 0, 4) }}
                                                        @else
                                                            {{ __('common.unknown') }}
                                                        @endif
		    									    </span>
                                <span class="badge-extra {{ rating_color($recommendation->vote_average ?? 'text-white') }}">
		    										    <i class="{{ config('other.font-awesome') }} fa-star-half-alt"></i> {{ $recommendation->vote_average ?? 0 }}/10
		    									    </span>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h4 class="text-bold text-danger">
                        <i class="{{ config('other.font-awesome') }} fa-frown"></i> No Recommendations Found!
                    </h4>
                </div>
            @endforelse
            @endif
        </div>
    </section>
</div>
