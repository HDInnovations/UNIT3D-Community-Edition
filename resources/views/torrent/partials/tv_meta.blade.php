<section class="meta">
    @if ($meta?->backdrop)
        <img class="meta__backdrop" src="{{ tmdb_image('back_big', $meta->backdrop) }}" alt="Backdrop">
    @endif
    <a class="meta__title-link" href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $tmdb]) }}">
        <h1 class="meta__title">
            {{ $meta->name ?? 'No Meta Found' }} ({{ substr($meta->first_air_date ?? '', 0, 4) ?? '' }})
        </h1>
    </a>
    <a class="meta__poster-link" href="{{ route('torrents.similar', ['category_id' => $category->id, 'tmdb' => $tmdb]) }}">
        <img
            src="{{ $meta?->poster ? tmdb_image('poster_big', $meta->poster) : 'https://via.placeholder.com/400x600' }}"
            class="meta__poster"
        >
    </a>
    <div class="meta__actions">
        <a class="meta__dropdown-button" href="#">
            <i class="{{ config('other.font-awesome') }} fa-ellipsis-v"></i>
        </a>
        <ul class="meta__dropdown">
            <li>
                <a href="{{ route('upload_form', ['category_id' => $category->id, 'title' => rawurlencode($meta?->name ?? $meta?->title ?? '') ?? 'Unknown', 'imdb' => $torrent->imdb ?? 0, 'tmdb' => $tmdb]) }}">
                    {{ __('common.upload') }}
                </a>
            </li>
            <li>
                <a href="{{ route('add_request_form', ['title' => rawurlencode($meta?->title ?? ''), 'imdb' => $torrent->imdb ?? '', 'tmdb' => $tmdb]) }}">
                    Request similar
                </a>
            </li>
        </ul>
    </div>
    <ul class="meta__ids">
        @if ($torrent->imdb ?? 0 > 0)
            <li class="meta__imdb">
                <a
                    class="meta-id-tag"
                    href="https://www.imdb.com/title/tt{{ \str_pad((int) $torrent->imdb, \max(\strlen((int) $torrent->imdb), 7), '0', STR_PAD_LEFT) }}"
                    title="Internet Movie Database"
                    target="_blank"
                >
                    IMDB: {{ \str_pad((int) $torrent->imdb, \max(\strlen((int) $torrent->imdb), 7), '0', STR_PAD_LEFT) }}
                </a>
            </li>
        @endif
        @if ($tmdb > 0)
            <li class="meta__tmdb">
                <a
                    class="meta-id-tag"
                    href="https://www.themoviedb.org/tv/{{ $tmdb }}"
                    title="The Movie Database"
                    target="_blank"
                >
                    TMDB: {{ $tmdb }}
                </a>
            </li>
        @endif
        @if ($torrent->mal ?? 0 > 0)
            <li class="meta__mal">
                <a
                    class="meta-id-tag"
                    href="https://myanimelist.net/anime/{{ $torrent->mal }}"
                    title="MyAnimeList"
                    target="_blank"
                >
                    MAL: {{ $torrent->mal }}
                </a>
            </li>
        @endif
        @if ($torrent->tvdb ?? 0 > 0)
            <li class="meta__tvdb">
                <a
                    class="meta-id-tag"
                    href="https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}"
                    title="MyAnimeList"
                    target="_blank"
                >
                    TVDB: {{ $torrent->tvdb }}
                </a>
            </li>
        @endif
    </ul>
    <p class="meta__description">{{ $meta?->overview }}</p>
    <div class="meta__chips">
        <section class="meta__chip-container">
            <h2 class="meta__heading">Cast</h2>
            @foreach ($meta?->credits?->where('occupation_id', '=', App\Enums\Occupations::ACTOR->value)?->sortBy('order') ?? [] as $credit)
                <article class="meta-chip-wrapper">
                    <a href="{{ route('mediahub.persons.show', ['id' => $credit->person->id]) }}" class="meta-chip">
                        @if ($credit->person->still)
                            <img
                                class="meta-chip__image"
                                src="{{ tmdb_image('cast_face', $credit->person->still) }}"
                                alt=""
                            />
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-user meta-chip__icon"></i>
                        @endif
                        <h2 class="meta-chip__name">{{ $credit->person->name }}</h2>
                        <h3 class="meta-chip__value">{{ $credit->character }}</h3>
                    </a>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container" title="Crew">
            <h2 class="meta__heading">Crew</h2>
            @foreach($meta?->credits?->where('occupation_id', '!=', App\Enums\Occupations::ACTOR->value)?->sortBy('occupation.position') ?? [] as $credit)
                <article class="meta-chip-wrapper">
                    <a href="{{ route('mediahub.persons.show', ['id' => $credit->person->id]) }}" class="meta-chip">
                        @if ($credit->person->still)
                            <img
                                class="meta-chip__image"
                                src="{{ tmdb_image('cast_face', $credit->person->still) }}"
                                alt=""
                            />
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-user meta-chip__icon"></i>
                        @endif
                        <h2 class="meta-chip__name">{{ $credit->occupation->name }}</h2>
                        <h3 class="meta-chip__value">{{ $credit->person->name }}</h3>
                    </a>
                </article>
            @endforeach
        </section>
        <section class="meta__chip-container">
            <h2 class="meta__heading">Extra Information</h2>
            <article class="meta-chip-wrapper meta-chip">
                <i class="{{ config('other.font-awesome') }} fa-star meta-chip__icon"></i>
                <h2 class="meta-chip__name">{{ __('torrent.rating') }}</h2>
                <h3 class="meta-chip__value">{{ ($meta->vote_average ?? 0) * 10 }}% / {{ $meta->vote_count ?? 0 }} {{ __('torrent.votes') }}</h3>
            </article>
            @isset($trailer)
                <article class="meta__trailer show-trailer">
                    <a class="meta-chip" href="#">
                        <i class="{{ config('other.font-awesome') }} fa-external-link meta-chip__icon"></i>
                        <h2 class="meta-chip__name">Trailer</h2>
                        <h3 class="meta-chip__value">View</h3>
                    </a>
                </article>
            @endisset
            @if ($meta?->genres?->isNotEmpty())
                <article class="meta__genres">
                    <a class="meta-chip" href="{{ route('torrents', ['view' => 'group', 'genres' => $meta->genres->pluck('id')->toArray()]) }}">
                        <i class="{{ config('other.font-awesome') }} fa-theater-masks meta-chip__icon"></i>
                        <h2 class="meta-chip__name">Genres</h2>
                        <h3 class="meta-chip__value">{{ $meta->genres->pluck('name')->join(' / ') }}</h3>
                    </a>
                </article>
            @endif
            @foreach ($meta?->networks ?? [] as $network)
                <article class="meta__company">
                    <a class="meta-chip" href="{{ route('torrents', ['view' => 'group', 'networkId' => $network->id]) }}">
                        @if ($network->logo)
                            <img class="meta-chip__image" style="object-fit: scale-down" src="{{ tmdb_image('logo_small', $network->logo) }}" alt="logo" />
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-signal-stream meta-chip__icon"></i>
                        @endif
                        <h2 class="meta-chip__name">Network</h2>
                        <h3 class="meta-chip__value">{{ $network->name }}</h3>
                    </a>
                </article>
            @endforeach
            @foreach ($meta?->companies ?? [] as $company)
                <article class="meta__company">
                    <a class="meta-chip" href="{{ route('torrents', ['view' => 'group', 'companyId' => $company->id]) }}">
                        @if ($company->logo)
                            <img class="meta-chip__image" style="object-fit: scale-down" src="{{ tmdb_image('logo_small', $company->logo) }}" alt="logo" />
                        @else
                            <i class="{{ config('other.font-awesome') }} fa-camera-movie meta-chip__icon"></i>
                        @endif
                        <h2 class="meta-chip__name">Company</h2>
                        <h3 class="meta-chip__value">{{ $company->name }}</h3>
                    </a>
                </article>
            @endforeach
            @if (isset($torrent) && $torrent->keywords?->isNotEmpty())
                <article class="meta__keywords">
                    <a class="meta-chip" href="{{ route('torrents', ['view' => 'group', 'keywords' => $torrent->keywords->pluck('name')->join(', ')]) }}">
                        <i class="{{ config('other.font-awesome') }} fa-tag meta-chip__icon"></i>
                        <h2 class="meta-chip__name">Keywords</h2>
                        <h3 class="meta-chip__value">{{ $torrent->keywords->pluck('name')->join(', ') }}</h3>
                    </a>
                </article>
            @endif
        </section>
    </div>
</section>
