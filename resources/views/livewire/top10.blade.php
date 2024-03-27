<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">Top Titles</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="interval"
                        class="form__select"
                        type="date"
                        name="interval"
                        wire:model.live="interval"
                    >
                        <option value="day">Past Day</option>
                        <option value="week">Past Week</option>
                        <option value="month">Past Month</option>
                        <option value="year">Past Year</option>
                        <option value="all">All-time</option>
                    </select>
                    <label class="form__label form__label--floating" for="interval">Interval</label>
                </div>
            </div>
            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="metaType"
                        class="form__select"
                        type="date"
                        name="metaType"
                        wire:model.live="metaType"
                    >
                        @foreach ($metaTypes as $name => $type)
                            <option value="{{ $type }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="metaType">Category</label>
                </div>
            </div>
        </div>
    </header>
    <div class="panel__body torrent-search--poster__results">
        <div wire:loading.delay>Computing...</div>

        @switch($this->metaType)
            @case('movie_meta')
                @foreach ($works as $work)
                    <figure class="top10-poster">
                        <x-movie.poster
                            :movie="$work->movie"
                            :categoryId="$work->category_id"
                            :tmdb="$work->tmdb"
                        />
                        <figcaption
                            class="top10-poster__download-count"
                            title="{{ __('torrent.completed-times') }}"
                        >
                            {{ $work->download_count }}
                        </figcaption>
                    </figure>
                @endforeach

                @break
            @case('tv_meta')
                @foreach ($works as $work)
                    <figure class="top10-poster">
                        <x-tv.poster
                            :tv="$work->tv"
                            :categoryId="$work->category_id"
                            :tmdb="$work->tmdb"
                        />
                        <figcaption
                            class="top10-poster__download-count"
                            title="{{ __('torrent.completed-times') }}"
                        >
                            {{ $work->download_count }}
                        </figcaption>
                    </figure>
                @endforeach

                @break
        @endswitch
    </div>
</section>
