<section class="meta">
    @if (isset($meta) && $meta->artworks)
        <img
            class="meta__backdrop"
            src="https://images.igdb.com/igdb/image/upload/t_screenshot_big/{{ $meta->artworks[0]['image_id'] }}.jpg"
            alt="Backdrop"
        />
    @endif

    <a
        class="meta__title-link"
        href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $igdb]) }}"
    >
        <h1 class="meta__title">
            {{ $meta->name ?? 'No Meta Found' }}
            ({{ substr($meta->first_release_date ?? '', 0, 4) ?? '' }})
        </h1>
    </a>
    <a
        class="meta__poster-link"
        href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $igdb]) }}"
    >
        <img
            src="{{ $meta?->cover ? 'https://images.igdb.com/igdb/image/upload/t_original/' . $meta->cover['image_id'] . '.jpg' : 'https://via.placeholder.com/400x600' }}"
            class="meta__poster"
        />
    </a>
    <div class="meta__actions">
        <a class="meta__dropdown-button" href="#">
            <i class="{{ config('other.font-awesome') }} fa-ellipsis-v"></i>
        </a>
        <ul class="meta__dropdown">
            <li>
                <a
                    href="{{
                        route('torrents.create', [
                            'category_id' => $category->id,
                            'title' => rawurlencode(($meta?->name ?? '') . ' ' . substr($meta->release_date ?? '', 0, 4) ?? ''),
                            'imdb' => $torrent->imdb ?? '',
                            'tmdb' => $torrent->tmdb ?? '',
                            'mal' => $torrent->mal ?? '',
                            'tvdb' => $torrent->tvdb ?? '',
                            'igdb' => $torrent->igdb ?? '',
                        ])
                    }}"
                >
                    {{ __('common.upload') }}
                </a>
            </li>
            <li>
                <a
                    href="{{
                        route('requests.create', [
                            'category_id' => $category->id,
                            'title' => rawurlencode(($meta?->name ?? '') . ' ' . substr($meta->release_date ?? '', 0, 4) ?? ''),
                            'imdb' => $torrent->imdb ?? '',
                            'tmdb' => $torrent->tmdb ?? '',
                            'mal' => $torrent->mal ?? '',
                            'tvdb' => $torrent->tvdb ?? '',
                            'igdb' => $torrent->igdb ?? '',
                        ])
                    }}"
                >
                    Request similar
                </a>
            </li>
        </ul>
    </div>
    <ul class="meta__ids">
        @if ($igdb > 0 && $meta->url)
            <li class="meta__tvdb">
                <a class="meta-id-tag" href="{{ $meta->url }}" title="IGDB" target="_blank">
                    IGDB: {{ $igdb }}
                </a>
            </li>
        @endif
    </ul>
    <p class="meta__description">{{ $meta?->summary ?? '' }}</p>
    <div class="meta__chips">
        <section class="meta__chip-container">
            <h2 class="meta__heading">Platforms</h2>
            @foreach ($platforms ?? [] as $platform)
                <article class="meta-chip-wrapper meta-chip">
                    @if ($platform->image_id)
                        <img
                            class="meta-chip__image"
                            src="https://images.igdb.com/igdb/image/upload/t_logo_med/{{ $platform->image_id }}.png"
                            alt=""
                        />
                    @else
                        <i class="{{ config('other.font-awesome') }} fa-user meta-chip__icon"></i>
                    @endif
                    <h2 class="meta-chip__name"></h2>
                    <h3 class="meta-chip__value"></h3>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container">
            <h2 class="meta__heading">Companies</h2>
            @foreach ($meta?->involved_companies ?? [] as $company)
                <article class="meta__company">
                    <a class="meta-chip" href="{{ $company['company']['url'] }}" target="_blank">
                        @if (array_key_exists('logo', $company['company']))
                            <img
                                class="meta-chip__image"
                                style="object-fit: scale-down"
                                src="https://images.igdb.com/igdb/image/upload/t_logo_med/{{ $company['company']['logo']['image_id'] }}.png"
                                alt="logo"
                            />
                        @else
                            <i
                                class="{{ config('other.font-awesome') }} fa-camera-movie meta-chip__icon"
                            ></i>
                        @endif
                        <h2 class="meta-chip__name">Company</h2>
                        <h3 class="meta-chip__value">{{ $company['company']['name'] }}</h3>
                    </a>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container">
            <h2 class="meta__heading">Extra Information</h2>
            <article class="meta-chip-wrapper meta-chip">
                <i class="{{ config('other.font-awesome') }} fa-star meta-chip__icon"></i>
                <h2 class="meta-chip__name">{{ __('torrent.rating') }}</h2>
                <h3 class="meta-chip__value">
                    {{ round($meta->rating ?? 0) }}% ({{ $meta->rating_count ?? 0 }}
                    {{ __('torrent.votes') }})
                </h3>
            </article>
            @isset($trailer)
                <article class="meta__trailer show-trailer">
                    <a class="meta-chip" href="#">
                        <i
                            class="{{ config('other.font-awesome') }} fa-external-link meta-chip__icon"
                        ></i>
                        <h2 class="meta-chip__name">Trailer</h2>
                        <h3 class="meta-chip__value">View</h3>
                    </a>
                </article>
            @endisset

            @if ($meta->genres !== [] && $meta->genres !== null)
                <article class="meta__genres meta-chip">
                    <i
                        class="{{ config('other.font-awesome') }} fa-theater-masks meta-chip__icon"
                    ></i>
                    <h2 class="meta-chip__name">Genres</h2>
                    <h3 class="meta-chip__value">
                        {{ implode(' / ', array_map(fn ($genre) => $genre['name'], $meta->genres)) }}
                    </h3>
                </article>
            @endif
        </section>
    </div>
</section>
