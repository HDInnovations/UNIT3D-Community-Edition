@if ($featured->isNotEmpty())
    <section class="panelV2 blocks__featured" x-data>
        <header class="panel__header">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-star"></i>
                {{ __('blocks.featured-torrents') }}
            </h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <button
                        class="form__standard-icon-button"
                        x-on:click="
                            $refs.featured.scrollLeft == 16
                                ? ($refs.featured.scrollLeft = $refs.featured.scrollWidth)
                                : ($refs.featured.scrollLeft -= ($refs.featured.children[0].offsetWidth + 16) / 2 + 2)
                        "
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-angle-left"></i>
                    </button>
                </div>
                <div class="panel__action">
                    <button
                        class="form__standard-icon-button"
                        x-on:click="
                            $refs.featured.scrollLeft == $refs.featured.scrollWidth - $refs.featured.offsetWidth - 16
                                ? ($refs.featured.scrollLeft = 0)
                                : ($refs.featured.scrollLeft += ($refs.featured.children[0].offsetWidth + 16) / 2 + 2)
                        "
                    >
                        <i class="{{ \config('other.font-awesome') }} fa-angle-right"></i>
                    </button>
                </div>
            </div>
        </header>
        <div>
            <ul
                class="featured-carousel"
                x-ref="featured"
                x-init="
                    setInterval(function () {
                        if (! $root.matches(':hover')) {
                            $el.scrollLeft == $el.scrollWidth - $el.offsetWidth - 16
                                ? ($el.scrollLeft = 0)
                                : ($el.scrollLeft += ($el.children[0].offsetWidth + 16) / 2 + 2);
                        }
                    }, 5000)
                "
            >
                @foreach ($featured as $feature)
                    @if ($feature->torrent === null || $feature->torrent->status !== \App\Models\Torrent::APPROVED)
                        @continue
                    @endif

                    @php
                        $meta = match (true) {
                            $feature->torrent->category->tv_meta => App\Models\Tv::query()
                                ->with('genres', 'networks', 'seasons')
                                ->find($feature->torrent->tmdb ?? 0),
                            $feature->torrent->category->movie_meta => App\Models\Movie::query()
                                ->with('genres', 'companies', 'collection')
                                ->find($feature->torrent->tmdb ?? 0),
                            $feature->torrent->category->game_meta => MarcReichel\IGDBLaravel\Models\Game::query()
                                ->with(['artworks' => ['url', 'image_id'], 'genres' => ['name']])
                                ->find((int) $feature->torrent->igdb),
                            default => null,
                        };
                    @endphp

                    <li class="featured-carousel__slide">
                        <x-torrent.card :meta="$meta" :torrent="$feature->torrent" />
                        <footer class="featured-carousel__feature-details">
                            <p class="featured-carousel__featured-until">
                                {{ __('blocks.featured-until') }}:
                                <br />
                                <time
                                    datetime="{{ $feature->created_at->addDay(7) }}"
                                    title="{{ $feature->created_at->addDay(7) }}"
                                >
                                    {{ $feature->created_at->addDay(7)->toFormattedDateString() }}
                                    ({{ $feature->created_at->addDay(7)->diffForHumans() }}!)
                                </time>
                            </p>
                            <p class="featured-carousel__featured-by">
                                {{ __('blocks.featured-by') }}: {{ $feature->user->username }}!
                            </p>
                        </footer>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
