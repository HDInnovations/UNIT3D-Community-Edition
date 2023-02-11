<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.movies') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        class="form__text"
                        placeholder=""
                        type="text"
                        wire:model.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating">
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $movies->links('partials.pagination') }}
    <div class="panel__body">
        @forelse($movies as $movie)
            <div class="col-md-12">
                <div class="card is-torrent" style=" height: 265px;">
                    <div class="card_head">
                        @if ($movie->companies)
                            @foreach ($movie->companies as $company)
                                <span class="badge-user text-bold" style="float:right;">
									{{ $company->name }}
								</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="card_body">
                        <div class="body_poster">
                            <img src="{{ isset($movie->poster) ? tmdb_image('poster_mid', $movie->poster) : 'https://via.placeholder.com/200x300' }}"
                                 class="show-poster">
                        </div>
                        <div class="body_description">
                            <h3 class="description_title">
                                <a href="{{ route('mediahub.movies.show', ['id' => $movie->id]) }}">{{ $movie->title }}
                                    @if($movie->release_date)
                                        <span class="text-bold text-pink"> {{ $movie->release_date }}</span>
                                    @endif
                                </a>
                            </h3>
                            @if ($movie->genres)
                                @foreach ($movie->genres as $genre)
                                    <span class="genre-label">{{ $genre->name }}</span>
                                @endforeach
                            @endif
                            <p class="description_plot">
                                {{ $movie->overview }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            No movies.
        @endforelse
    </div>
    {{ $movies->links('partials.pagination') }}
</section>
