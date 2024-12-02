<section
    @class([
        'panelV2',
        'top10',
        'top10--weekly' => $this->interval === 'weekly',
    ])
>
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
                        <option value="weekly">Weekly</option>
                        <option value="custom">Custom</option>
                    </select>
                    <label class="form__label form__label--floating" for="interval">Interval</label>
                </div>
            </div>
            @if ($this->interval === 'custom')
                <div class="panel__action">
                    <div class="form__group">
                        <input
                            id="from"
                            class="form__text"
                            name="from"
                            type="date"
                            wire:model.live="from"
                        />
                        <label class="form__label form__label--floating" for="from">From</label>
                    </div>
                </div>
                <div class="panel__action">
                    <div class="form__group">
                        <input
                            id="until"
                            class="form__text"
                            name="until"
                            type="date"
                            wire:model.live="until"
                        />
                        <label class="form__label form__label--floating" for="until">Until</label>
                    </div>
                </div>
            @endif

            <div class="panel__action">
                <div class="form__group">
                    <select
                        id="metaType"
                        class="form__select"
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
    @if ($this->interval === 'weekly')
        <div class="data-table-wrapper">
            <div wire:loading.delay class="panel__body">Computing...</div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Rankings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($works as $weeklyRankings)
                        <tr>
                            <th>
                                {{ $weeklyRankings->first()?->week_start?->format('Y-m-d') }}
                            </th>
                            <td class="panel__body top10-weekly__row">
                                @foreach ($weeklyRankings as $ranking)
                                    <figure class="top10-poster">
                                        @switch($this->metaType)
                                            @case('movie_meta')
                                                <x-movie.poster
                                                    :movie="$ranking->movie"
                                                    :categoryId="$ranking->category_id"
                                                    :tmdb="$ranking->tmdb"
                                                />

                                                @break
                                            @case('tv_meta')
                                                <x-tv.poster
                                                    :tv="$ranking->tv"
                                                    :categoryId="$ranking->category_id"
                                                    :tmdb="$ranking->tmdb"
                                                />

                                                @break
                                        @endswitch
                                        <figcaption
                                            class="top10-poster__download-count"
                                            title="{{ __('torrent.completed-times') }}"
                                        >
                                            {{ $ranking->download_count }}
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
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
    @endif
</section>
