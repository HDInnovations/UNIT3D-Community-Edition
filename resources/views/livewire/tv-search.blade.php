<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.shows') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        placeholder=" "
                        type="text"
                        wire:model.live.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $shows->links('partials.pagination') }}
    <div class="panel__body">
        @foreach ($shows as $show)
            <div class="col-md-12">
                <div class="card is-torrent" style="height: 265px">
                    <div class="card_head">
                        <span class="text-bold" style="float: right">
                            {{ $show->seasons_count }} Seasons
                        </span>
                        @if ($show->networks)
                            @foreach ($show->networks as $network)
                                <span class="text-bold" style="float: right">
                                    {{ $network->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                    <div class="card_body">
                        <div class="body_poster">
                            <img
                                src="{{ isset($show->poster) ? tmdb_image('poster_mid', $show->poster) : 'https://via.placeholder.com/200x300' }}"
                                class="show-poster"
                            />
                        </div>
                        <div class="body_description">
                            <h3 class="description_title">
                                <a href="{{ route('mediahub.shows.show', ['id' => $show->id]) }}">
                                    {{ $show->name }}
                                    @if ($show->first_aired)
                                        <span class="text-bold text-pink">
                                            {{ $show->first_aired }}
                                        </span>
                                    @endif
                                </a>
                            </h3>
                            @if ($show->genres)
                                @foreach ($show->genres as $genre)
                                    <span class="genre-label">{{ $genre->name }}</span>
                                @endforeach
                            @endif

                            <p class="description_plot">
                                {{ $show->overview }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $shows->links('partials.pagination') }}
</section>
