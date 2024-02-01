@props([
    'id',
    'poster',
    'name',
    'release_date',
])

<article class="torrent-search--poster__result">
    <figure>
        <a
            href="{{ route('torrents.show', ['id' => $id]) }}"
            class="torrent-search--poster__poster"
        >
            <img
                src="{{ isset($poster) ? tmdb_image('poster_mid', $poster) : 'https://via.placeholder.com/90x135' }}"
                alt="{{ __('torrent.poster') }}"
                loading="lazy"
            />
        </a>
        <figcaption class="torrent-search--poster__caption">
            <h2 class="torrent-search--poster__title">
                {{ $name ?? '' }}
            </h2>
            <h3 class="torrent-search--poster__release-date">
                {{ substr($release_date ?? '', 0, 4) ?? '' }}
            </h3>
        </figcaption>
    </figure>
</article>
