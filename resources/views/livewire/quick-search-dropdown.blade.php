<div
    class="quick-search"
    x-data="{ search: 'movie', ...quickSearchKeyboardNavigation() }"
    x-on:keydown.escape.window="$refs.movieSearch.blur(); $refs.seriesSearch.blur(); $refs.personSearch.blur()"
>
    <div class="quick-search__inputs">
        <div class="quick-search__radios">
            <label class="quick-search__radio-label">
                <input
                    type="radio"
                    class="quick-search__radio"
                    x-on:click="
                        search = 'movie';
                        $wire.set('series', '');
                        $wire.set('person', '');
                        $nextTick(() => $refs.movieSearch.focus());
                    "
                />
                <i
                    class="quick-search__radio-icon {{ \config('other.font-awesome') }} fa-camera-movie"
                    title="{{ __('mediahub.movies') }}"
                ></i>
            </label>
            <label class="quick-search__radio-label">
                <input
                    type="radio"
                    class="quick-search__radio"
                    x-on:click="
                        search = 'series';
                        $wire.set('movie', '');
                        $wire.set('person', '');
                        $nextTick(() => $refs.seriesSearch.focus());
                    "
                />
                <i
                    class="quick-search__radio-icon {{ \config('other.font-awesome') }} fa-tv-retro"
                    title="{{ __('mediahub.shows') }}"
                ></i>
            </label>
            <label class="quick-search__radio-label">
                <input
                    type="radio"
                    class="quick-search__radio"
                    x-on:click="
                        search = 'person';
                        $wire.set('movie', '');
                        $wire.set('series', '');
                        $nextTick(() => $refs.personSearch.focus());
                    "
                />
                <i
                    class="quick-search__radio-icon {{ \config('other.font-awesome') }} fa-user"
                    title="{{ __('mediahub.persons') }}"
                ></i>
            </label>
        </div>
        <input
            class="quick-search__input"
            wire:model.debounce.250ms="movie"
            type="text"
            placeholder="Movie"
            x-show="search == 'movie'"
            x-ref="movieSearch"
            x-on:keydown.down.prevent="$refs.searchResults.firstElementChild?.firstElementChild?.focus()"
            x-on:keydown.up.prevent="$refs.searchResults.lastElementChild?.firstElementChild?.focus()"
        />
        <input
            wire:model.debounce.250ms="series"
            class="quick-search__input"
            type="text"
            placeholder="Series"
            x-show="search == 'series'"
            x-ref="seriesSearch"
            x-cloak
            x-on:keydown.down.prevent="$refs.searchResults.firstElementChild?.firstElementChild?.focus()"
            x-on:keydown.up.prevent="$refs.searchResults.lastElementChild?.firstElementChild?.focus()"
        />
        <input
            wire:model.debounce.250ms="person"
            class="quick-search__input"
            type="text"
            placeholder="Person"
            x-show="search == 'person'"
            x-ref="personSearch"
            x-cloak
            x-on:keydown.down.prevent="$refs.searchResults.firstElementChild?.lastElementChild?.focus()"
            x-on:keydown.up.prevent="$refs.searchResults.lastElementChild?.firstElementChild?.focus()"
        />
        @if (strlen($movie) >= 3  || strlen($series) >= 3  || strlen($person) >= 3)
            <div class="quick-search__results" x-ref="searchResults">
                @forelse ($search_results as $search_result)
                    <article
                        class="quick-search__result"
                        x-on:keydown.down.prevent="quickSearchArrowDown($el)"
                        x-on:keydown.up.prevent="quickSearchArrowUp($el)"
                    >
                        @if (strlen($movie) >= 3 )
                            <a
                                class="quick-search__result-link"
                                href="{{ route('torrents.similar', ['category_id' => '1', 'tmdb' => $search_result->id]) }}"
                            >
                                <img
                                    class="quick-search__image"
                                    src="{{ $search_result->poster }}"
                                    alt="{{ __('torrent.poster') }}"
                                />
                                <h2 class="quick-search__result-text">
                                    {{ $search_result->title }}
                                    <time
                                        class="quick-search__result-year"
                                        datetime="{{ $search_result->release_date }}"
                                    >
                                        {{ substr($search_result->release_date, 0, 4) }}
                                    </time>
                                </h2>
                            </a>
                        @elseif (strlen($series) >= 3 )
                            <a
                                class="quick-search__result-link"
                                href="{{ route('torrents.similar', ['category_id' => '2', 'tmdb' => $search_result->id]) }}"
                            >
                                <img
                                    class="quick-search__image"
                                    src="{{ $search_result->poster }}"
                                    alt="{{ __('torrent.poster') }}"
                                />
                                <h2 class="quick-search__result-text">
                                    {{ $search_result->name }}
                                    <time
                                        class="quick-search__result-year"
                                        datetime="{{ $search_result->first_air_date }}"
                                    >
                                        {{ substr($search_result->first_air_date, 0, 4) }}
                                    </time>
                                </h2>
                            </a>
                        @elseif (strlen($person) >= 3 )
                            <a
                                class="quick-search__result-link"
                                href="{{ route('mediahub.persons.show', ['id' => $search_result->id]) }}"
                            >
                                <img
                                    class="quick-search__image"
                                    src="{{ $search_result->still }}"
                                    alt="{{ __('torrent.poster') }}"
                                />
                                <h2 class="quick-search__result-text">
                                    {{ $search_result->name }}
                                </h2>
                            </a>
                        @endif
                    </article>
                @empty
                    <article class="quick-search__result--empty">
                        <p class="quick-search__result-text">No results found</p>
                    </article>
                @endforelse
            </div>
        @else
            <div class="quick-search__results">
                <article class="quick-search__result--keep-typing">
                    <p class="quick-search__result-text">Keep typing to get results</p>
                </article>
            </div>
        @endif
    </div>
</div>
<script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
    function quickSearchKeyboardNavigation() {
        return {
            quickSearchArrowDown(el) {
                if (el.nextElementSibling == null) {
                    el.parentNode?.firstElementChild?.firstElementChild?.focus()
                } else {
                    el.nextElementSibling?.firstElementChild?.focus()
                }
            },
            quickSearchArrowUp(el) {
                if (el.previousElementSibling == null) {
                    document.querySelector(`.quick-search__input:not([style='display: none;'])`)?.focus()
                } else {
                    el.previousElementSibling?.firstElementChild?.focus()
                }        
            }
        }
    }
</script>
