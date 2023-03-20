<div class="panel__body">
    <section class="recommendations" style="max-height: 330px !important;">
        <div class="scroller" style="padding-bottom: 10px;">
            @forelse($meta->recommendations ?? [] as $recommendation)
                <div class="item mini backdrop mini_card">
                    <div class="image_content">
                    <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $recommendation->recommendation_movie_id ?? $recommendation->recommendation_tv_id]) }}">
                        @isset($recommendation->poster)
                            <img
                                class="backdrop"
                                src="{{ tmdb_image('poster_big', $recommendation->poster) }}"
                            >
                        @else
                            <div class="no_image_holder w300_and_h450 backdrop"></div>
                        @endif
                        <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 12px;">
                            <span>
                                @isset($recommendation->release_date)
                                    {{ substr($recommendation->release_date, 0, 4) }}
                                @else($recommendation->first_air_date)
                                    {{ substr($recommendation->first_air_date, 0, 4) }}
                                @endif
                            </span>
                            <span class="{{ rating_color($recommendation->vote_average) ?? 'text-white' }}">
                                {{ ($recommendation->vote_average ?? 0) * 10 }}%
                            </span>
                        </div>
                    </a>
                    </div>
                </div>
            @empty
                No Recommendations Found!
            @endforelse
        </div>
    </section>
</div>
