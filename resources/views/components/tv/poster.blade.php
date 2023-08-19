<article class="torrent-search--poster__result">
    <figure>
        <a
            href="{{ route('torrents.similar', ['category_id' => $media->category_id, 'tmdb' => $media->tmdb]) }}"
            class="torrent-search--poster__poster"
        >
            <img
                src="{{ isset($media->tv->poster) ? tmdb_image('poster_mid', $media->tv->poster) : 'https://via.placeholder.com/90x135' }}"
                alt="{{ __('torrent.poster') }}"
                loading="lazy"
            >
        </a>
        <figcaption class="torrent-search--poster__caption">
            <h2 class="torrent-search--poster__title">
                {{ $media->tv->name ?? '' }}
            </h2>
            <h3 class="torrent-search--poster__release-date">
                <time>
                    {{ substr($media->tv->first_air_date ?? '', 0, 4) ?? '' }}
                </time>
            </h3>
        </figcaption>
    </figure>
</article>
