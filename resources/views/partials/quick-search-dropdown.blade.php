<div class="quick-search" x-data="quickSearch()" x-on:keydown.escape.window="clearSearch()">
    <div class="quick-search__inputs">
        <input
            class="quick-search__input"
            type="text"
            placeholder="Search Movie, TV Series or People"
            x-model="searchText"
            x-on:input.debounce.100ms="performSearch"
            x-ref="quickSearch"
            x-on:keydown.down.prevent="focusFirstResult"
            x-on:keydown.up.prevent="focusLastResult"
            x-on:focus="searchPerformed = true"
        />
        <template x-if="searchPerformed && searchResults.length === 0">
            <div class="quick-search__results">
                <article class="quick-search__result--empty">
                    <p class="quick-search__result-text">No results found</p>
                </article>
            </div>
        </template>
        <template x-if="searchResults.length > 0">
            <div class="quick-search__results" x-ref="searchResults">
                <template x-for="result in searchResults" :key="result.id">
                    <article
                        class="quick-search__result"
                        x-on:keydown.down.prevent="focusNextResult"
                        x-on:keydown.up.prevent="focusPreviousResult"
                    >
                        <a class="quick-search__result-link" :href="result.url">
                            <img class="quick-search__image" :src="result.image" alt="" />
                            <h2 class="quick-search__result-text">
                                <span x-text="result.name"></span>
                                <time
                                    class="quick-search__result-year"
                                    x-text="result.year"
                                ></time>
                                <span
                                    class="quick-search__result-type"
                                    x-text="result.type"
                                ></span>
                            </h2>
                        </a>
                    </article>
                </template>
            </div>
        </template>
    </div>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        function quickSearch() {
            return {
                searchText: '',
                searchResults: [],
                searchPerformed: false,
                performSearch() {
                    this.searchPerformed = true;
                    if (this.searchText.length === 0) {
                        this.searchResults = [];
                        return;
                    }

                    fetch(`/api/quicksearch?query=${encodeURIComponent(this.searchText)}`)
                        .then((response) => response.json())
                        .then((data) => {
                            this.searchResults = data.results.map((result) => {
                                return result;
                            });
                        });
                },
                clearSearch() {
                    this.searchText = '';
                    this.searchResults = [];
                    this.searchPerformed = false;
                },
                focusFirstResult() {
                    document.querySelector('[x-ref="searchResults"]').querySelector('a').focus();
                },
                focusLastResult() {
                    document
                        .querySelector('[x-ref="searchResults"]')
                        .querySelector('article:last-child > a')
                        .focus();
                },
                focusNextResult() {
                    const el = this.$el;
                    if (el.nextElementSibling === null) {
                        el.parentNode?.firstElementChild?.nextElementSibling?.firstElementChild?.focus();
                    } else {
                        el.nextElementSibling?.firstElementChild?.focus();
                    }
                },
                focusPreviousResult() {
                    const el = this.$el;
                    if (el.previousElementSibling.tagName === 'TEMPLATE') {
                        this.$refs.quickSearch.focus();
                    } else {
                        el.previousElementSibling?.firstElementChild?.focus();
                    }
                },
            };
        }
    </script>
</div>
