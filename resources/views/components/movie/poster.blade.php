<article class="torrent-search--poster__result">
    <figure>
        <a
            href="{{ route('torrents.similar', ['category_id' => $media->category_id, 'tmdb' => $media->movie_id ?: $media->tv_id]) }}"
            class="torrent-search--poster__poster"
        >
            <img
                src="{{ isset($media->movie->poster) ? tmdb_image('poster_mid', $media->movie->poster) : 'https://via.placeholder.com/90x135' }}"
                alt="{{ __('torrent.poster') }}"
                loading="lazy"
            >
        </a>
        <figcaption class="torrent-search--poster__caption">
            <h2 class="torrent-search--poster__title">
                {{ $media->movie->title ?? '' }}
            </h2>
            <h3 class="torrent-search--poster__release-date">
                {{ substr($media->movie->release_date ?? '', 0, 4) ?? '' }}
            </h3>
        </figcaption>
    </figure>
</article>
