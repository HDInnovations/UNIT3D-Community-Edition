<div>
    <div class="container-fluid">
        <style>
            .form-group {
                margin-bottom: 5px !important;
            }

            .badge-extra {
                margin-bottom: 0;
            }
        </style>
        <div x-data="{ open: false }" class="container box" id="torrent-list-search"
             style="margin-bottom: 0; padding: 10px 100px; border-radius: 5px;">
            <div class="mt-5">
                <div class="row">
                    <div class="form-group col-xs-9">
                        <input wire:model.debounce.500ms="name" type="search" class="form-control" placeholder="Name"/>
                    </div>
                    <div class="form-group col-xs-3">
                        <button class="btn btn-md btn-primary" @click="open = ! open"
                                x-text="open ? '{{ __('common.search-hide') }}' : '{{ __('common.search-advanced') }}'">
                        </button>
                    </div>
                </div>
                <div x-cloak x-show="open" id="torrent-advanced-search">
                    <div class="row">
                        <div class="form-group col-sm-3 col-xs-6 adv-search-description">
                            <label for="description" class="label label-default">{{ __('torrent.description') }}</label>
                            <input wire:model.debounce.500ms="description" type="text" class="form-control"
                                   placeholder="Description">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-mediainfo">
                            <label for="mediainfo" class="label label-default">{{ __('torrent.media-info') }}</label>
                            <input wire:model.debounce.500ms="mediainfo" type="text" class="form-control"
                                   placeholder="Mediainfo">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-keywords">
                            <label for="keywords" class="label label-default">{{ __('torrent.keywords') }}</label>
                            <input wire:model.debounce.500ms="keywords" type="text" class="form-control"
                                   placeholder="Keywords">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-uploader">
                            <label for="uploader" class="label label-default">{{ __('torrent.uploader') }}</label>
                            <input wire:model.debounce.500ms="uploader" type="text" class="form-control"
                                   placeholder="Uploader">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3 col-xs-6 adv-search-tmdb">
                            <label for="tmdbId" class="label label-default">TMDb</label>
                            <input wire:model.debounce.500ms="tmdbId" type="text" class="form-control"
                                   placeholder="TMDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-imdb">
                            <label for="imdbId" class="label label-default">IMDb</label>
                            <input wire:model.debounce.500ms="imdbId" type="text" class="form-control"
                                   placeholder="IMDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-tvdb">
                            <label for="tvdbId" class="label label-default">TVDb</label>
                            <input wire:model.debounce.500ms="tvdbId" type="text" class="form-control"
                                   placeholder="TVDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-mal">
                            <label for="malId" class="label label-default">MAL</label>
                            <input wire:model.debounce.500ms="malId" type="text" class="form-control"
                                   placeholder="MAL ID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3 col-xs-6 adv-search-startYear">
                            <label for="startYear" class="label label-default">{{ __('torrent.start-year') }}</label>
                            <input wire:model.debounce.500ms="startYear" type="text" class="form-control"
                                   placeholder="Start Year">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-endYear">
                            <label for="endYear" class="label label-default">{{ __('torrent.end-year') }}</label>
                            <input wire:model.debounce.500ms="endYear" type="text" class="form-control"
                                   placeholder="End Year">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-playlist">
                            <label for="playlist" class="label label-default">Playlist</label>
                            <input wire:model.debounce.500ms="playlistId" type="text" class="form-control"
                                   placeholder="Playlist ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-collection">
                            <label for="collection" class="label label-default">Collection</label>
                            <input wire:model.debounce.500ms="collectionId" type="text" class="form-control"
                                   placeholder="Collection ID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-xs-12 adv-search-region">
                            @php $regions = cache()->remember('regions', 3_600, fn () => App\Models\Region::all()->sortBy('position')) @endphp
                            <label for="region" class="label label-default">Region</label>
                            <div id="regions" wire:ignore></div>
                        </div>
                        <div class="form-group col-sm-6 col-xs-12 adv-search-distributor">
                            @php $distributors = cache()->remember('distributors', 3_600, fn () => App\Models\Distributor::all()->sortBy('position')) @endphp
                            <label for="distributor" class="label label-default">Distributor</label>
                            <div id="distributors" wire:ignore></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-categories">
                            <label for="categories" class="label label-default">{{ __('common.category') }}</label>
                            @php $categories = cache()->remember('categories', 3_600, fn () => App\Models\Category::all()->sortBy('position')) @endphp
                            @foreach ($categories as $category)
                                <span class="badge-user">
									<label class="inline">
										<input type="checkbox" wire:model.prefetch="categories"
                                               value="{{ $category->id }}"> {{ $category->name }}
									</label>
								</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-types">
                            <label for="types" class="label label-default">{{ __('common.type') }}</label>
                            @php $types = cache()->remember('types', 3_600, fn () => App\Models\Type::all()->sortBy('position')) @endphp
                            @foreach ($types as $type)
                                <span class="badge-user">
									<label class="inline">
										<input type="checkbox" wire:model.prefetch="types" value="{{ $type->id }}"> {{ $type->name }}
									</label>
								</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-resolutions">
                            <label for="resolutions" class="label label-default">{{ __('common.resolution') }}</label>
                            @php $resolutions = cache()->remember('resolutions', 3_600, fn () => App\Models\Resolution::all()->sortBy('position')) @endphp
                            @foreach ($resolutions as $resolution)
                                <span class="badge-user">
									<label class="inline">
										<input type="checkbox" wire:model.prefetch="resolutions"
                                               value="{{ $resolution->id }}"> {{ $resolution->name }}
									</label>
								</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-genres">
                            <label for="genres" class="label label-default">{{ __('common.genre') }}</label>
                            @php $genres = cache()->remember('genres', 3_600, fn () => App\Models\Genre::all()->sortBy('name')) @endphp
                            @foreach ($genres as $genre)
                                <span class="badge-user">
									<label class="inline">
										<input type="checkbox" wire:model.prefetch="genres" value="{{ $genre->id }}"> {{ $genre->name }}
									</label>
								</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-buffs">
                            <label for="buffs" class="label label-default">Buff</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free" type="checkbox" value="0">
									0% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free" type="checkbox" value="25">
									25% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free" type="checkbox" value="50">
									50% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free" type="checkbox" value="75">
									75% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free" type="checkbox" value="100">
									100% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="doubleup" type="checkbox" value="1">
									Double Upload
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="featured" type="checkbox" value="1">
									Featured
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-tags">
                            <label for="tags" class="label label-default">Tags</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="stream" type="checkbox" value="1">
									Stream Optimized
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="sd" type="checkbox" value="1">
									SD Content
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="highspeed" type="checkbox" value="1">
									Highspeed
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-extra">
                            <label for="extra" class="label label-default">{{ __('common.extra') }}</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="internal" type="checkbox" value="1">
									Internal
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="personalRelease" type="checkbox" value="1">
									Personal Release
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-misc">
                            <label for="misc" class="label label-default">Misc</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="bookmarked" type="checkbox" value="1">
									Bookmarked
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="wished" type="checkbox" value="1">
									Wished
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-health">
                            <label for="health" class="label label-default">{{ __('torrent.health') }}</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="alive" type="checkbox" value="1">
									{{ __('torrent.alive') }}
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="dying" type="checkbox" value="1">
									{{ __('torrent.dying-torrent') }}
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="dead" type="checkbox" value="1">
									{{ __('torrent.dead-torrent') }}
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-history">
                            <label for="history" class="label label-default">{{ __('torrent.history') }}</label>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="notDownloaded" type="checkbox" value="1">
									Not Downloaded
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="downloaded" type="checkbox" value="1">
									Downloaded
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="seeding" type="checkbox" value="1">
									Seeding
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="leeching" type="checkbox" value="1">
									Leeching
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="incomplete" type="checkbox" value="1">
									Incomplete
								</label>
							</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-6 adv-search-quantity">
                            <label for="quantity" class="label label-default">{{ __('common.quantity') }}</label>
                            <span>
								<label class="inline">
								<select wire:model="perPage" class="form-control">
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select>
								</label>
							</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>
    <div class="text-center">
        {{-- $torrents->links() --}}
    </div>
    <br>
    <div class="torrent-search--grouped__results block">
        <div class="dropdown torrent-listings-action-bar">
            <a class="dropdown btn btn-xs btn-success" data-toggle="dropdown" href="#" aria-expanded="true">
                {{ __('common.publish') }} {{ __('torrent.torrent') }}
                <i class="fas fa-caret-circle-right"></i>
            </a>
            <ul class="dropdown-menu">
                @foreach($categories as $category)
                    <li role="presentation">
                        <a role="menuitem" tabindex="-1" target="_blank"
                           href="{{ route('upload_form', ['category_id' => $category->id]) }}">
                            <span class="menu-text">{{ $category->name }}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <style>
            /* Sorting row */
            .torrent-search--grouped__sorting {
                width: 100%;
                display: grid;
                grid-template-columns: 7fr 57fr 2.5fr 2.5fr 8.5fr 4fr 4fr 4fr 8.5fr;
                padding: 8px 2px 8px 40px;
                font-size: 13px;
            }

            /* All results */
            .torrent-search--grouped__results {
                max-width: 1200px;
                margin: 0 auto;
                /* --bg: #303030;
                --header-bg: #444;
                --text: #ddd;
                --text-muted: #888;
                --table-stripe: rgba(255, 255, 255, 0.03);
                --hover-brightness-emphasis: 1.2; */
                --bg: #091830;
                --header-bg: #2b384d;
                --text: white;
                --text-muted: #878787;
                --table-stripe: rgba(255, 255, 255, 0.05);
                --hover-brightness-emphasis: 1.2;
                --chip-border: rgba(255, 255, 255, 0.05)
            }

            /* Individual search result */
            .torrent-search--grouped__result {
                background-color: var(--bg);
                color: var(--text);
                margin: 16px 12px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
                overflow: hidden;
            }

            /* Styles for Header of grouped result */
            .torrent-search--grouped__header {
                display: grid;
                grid-template-areas:
                    'poster title directors genres'
                    'poster plot plot genres';
                grid-template-columns: auto auto 1fr auto;
                grid-template-rows: auto 1fr;
                background-color: var(--header-bg);
                height: 90px;
            }

            .torrent-search--grouped__poster {
                grid-area: poster;
                overflow: hidden;
            }

            .torrent-search--grouped__poster > img {
                height: 90px;
            }

            .torrent-search--grouped__title-name {
                grid-area: title;
                padding: 10px 0 0 10px;
                margin: 0;
                font-size: 16px;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            }

            .torrent-search--grouped__title-name time {
                color: inherit;
            }

            .torrent-search--grouped__plot {
                grid-area: plot;
                margin: 10px 10px;
                color: var(--text-muted);
                font-size: 12px;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                line-clamp: 2;
                -webkit-box-orient: vertical;
                word-wrap: break-word;
                text-overflow: ellipsis;
                overflow: hidden;
            }

            .torrent-search--grouped__directors {
                grid-area: directors;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
                margin: 0;
                padding: 5px 0 0 5px;
                font-size: 12px;
                color: var(--text);
                align-self: end;
                padding-left: 10px;
            }

            .torrent-search--grouped__director {
                font-size: 12px;
            }

            .torrent-search-grouped__directors-by {
                color: var(--text-muted);
            }

            .torrent-search--grouped__genres {
                margin: 4px;
                grid-area: genres;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
                display: flex;
                justify-content: space-evenly;
                align-items: flex-end;
                flex-direction: column;
            }

            .torrent-search--grouped__genre {
                padding: 4px 12px;
                border-radius: 500px;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
                font-size: 10px;
                text-align: center;
                color: var(--text);
                border: 2px solid var(--chip-border);
                line-height: 1.25;
            }

            .torrent-search--grouped__genre:hover {
                backdrop-filter: brightness(var(--hover-brightness-emphasis));
                transition: backdrop-filter .1s;
                color: var(--text) !important;
            }

            /* Season and episode dropdowns */
            .torrent-search--grouped__episode-dropdown > summary,
            .torrent-search--grouped__season-dropdown > summary,
            .torrent-search--grouped__season-pack-dropdown > summary,
            .torrent-search--grouped__complete-pack-dropdown > summary {
                padding: 6px;
                cursor: pointer;
                list-style: none;
                font-size: 14px;
                border-top: 1px solid var(--header-bg);
            }

            /* Remove border if its the first dropdown in the panel body or if it comes immediately after an open dropdown */
            .torrent-search--grouped__season-dropdown:first-of-type
            > summary,
            .torrent-search--grouped__complete-pack-dropdown:first-of-type
            > summary,
            .torrent-search--grouped__episode-dropdown[open]
            + .torrent-search--grouped__episode-dropdown
            > summary,
            .torrent-search--grouped__season-pack-dropdown[open]
            + .torrent-search--grouped__episode-dropdown
            > summary,
            .torrent-search--grouped__complete-pack-dropdown[open]
            + .torrent-search--grouped__season-dropdown
            > summary {
                border-width: 0;
            }

            .torrent-search--grouped__episode-dropdown > summary::before,
            .torrent-search--grouped__season-dropdown > summary::before,
            .torrent-search--grouped__season-pack-dropdown > summary::before,
            .torrent-search--grouped__complete-pack-dropdown > summary::before {
                content: '\f105';
                font-family: 'Font Awesome\ 5 Pro', sans-serif;
                color: var(--text);
                font-size: 12px;
                display: inline-block;
                padding: 0 6px;
            }

            .torrent-search--grouped__episode-dropdown[open] > summary::before,
            .torrent-search--grouped__season-dropdown[open] > summary::before,
            .torrent-search--grouped__season-pack-dropdown[open] > summary::before,
            .torrent-search--grouped__complete-pack-dropdown[open] > summary::before {
                content: '\f105';
                font-family: 'Font Awesome\ 5 Pro', sans-serif;
                padding: 0 4px 0 8px;
            }

            .torrent-search--grouped__episode-dropdown > summary:hover::before,
            .torrent-search--grouped__season-dropdown > summary:hover::before,
            .torrent-search--grouped__season-pack-dropdown > summary:hover::before,
            .torrent-search--grouped__complete-pack-dropdown > summary:hover::before {
                filter: brightness(calc(1 / var(--hover-brightness-emphasis)));
                transition: filter 0.1s;
            }

            .torrent-search--grouped__episode-dropdown,
            .torrent-search--grouped__season-dropdown,
            .torrent-search--grouped__season-pack-dropdown,
            .torrent-search--grouped__complete-pack-dropdown {
                display: block;
                font-size: 13px;
            }

            .torrent-search--grouped__season-dropdown > summary,
            .torrent-search--grouped__complete-pack-dropdown > summary {
                padding-left: 16px;
            }

            .torrent-search--grouped__season-pack-dropdown > summary,
            .torrent-search--grouped__episode-dropdown > summary {
                padding-left: 32px;
            }

            /* Individual table of episodes/seasons */
            .torrent-search--grouped__movie-torrents,
            .torrent-search--grouped__episode-torrents,
            .torrent-search--grouped__season-pack-torrents,
            .torrent-search--grouped__complete-pack-torrents {
                margin-left: 0px;
                width: 100%;
                table-layout: fixed;
                border-width: 3px 0 !important;
                border-style: solid !important;
                border-color: var(--header-bg) !important;
            }

            .torrent-search--grouped__header + section > .torrent-search--grouped__movie-torrents:first-child {
                border-width: 0 !important;
            }

            .torrent-search--grouped__movie-torrents tbody:nth-child(odd),
            .torrent-search--grouped__episode-torrents tbody:nth-child(odd),
            .torrent-search--grouped__season-pack-torrents tbody:nth-child(odd),
            .torrent-search--grouped__complete-pack-torrents tbody:nth-child(odd) {
                background-color: var(--table-stripe);
            }

            .torrent-search--grouped__movie-torrents > tbody > tr,
            .torrent-search--grouped__episode-torrents > tbody > tr,
            .torrent-search--grouped__season-pack-torrents > tbody > tr,
            .torrent-search--grouped__complete-pack-torrents > tbody > tr {
                border-bottom: 0;
            }

            /* Buttons in table */
            .torrent-search--grouped__download a,
            .torrent-search--grouped__bookmark button,
            a.torrent-search--grouped__edit {
                padding: 4px;
                font-size: 12px;
                background: initial;
                display: inline-block;
                width: 100%;
                height: 100%;
            }

            .torrent-search--grouped__download a:hover,
            .torrent-search--grouped__bookmark button:hover,
            a.torrent-search--grouped__edit:hover {
                color: inherit !important;
                filter: brightness(calc(1 / var(--hover-brightness-emphasis)));
            }

            /* Table cells */
            .torrent-search--grouped__movie-torrents tr:last-child,
            .torrent-search--grouped__episode-torrents tr:last-child,
            .torrent-search--grouped__season-pack-torrents tr:last-child,
            .torrent-search--grouped__complete-pack-torrents tr:last-child,
            th.torrent-search--grouped__type,
            td.torrent-search--grouped__overview,
            td.torrent-search--grouped__download,
            td.torrent-search--grouped__bookmark,
            td.torrent-search--grouped__size,
            td.torrent-search--grouped__seeders,
            td.torrent-search--grouped__leechers,
            td.torrent-search--grouped__completed,
            td.torrent-search--grouped__age {
                background-color: initial !important;
                font-size: 13px;
                font-weight: normal;
                padding: 2px 4px;
                min-height: 36px;
                vertical-align: middle;
            }

            .torrent-search--grouped__type {
                text-align: right;
                padding-right: 20px;
            }

            .torrent-search--grouped__name {
                font-family: inherit;
                display: inline;
                font-size: 13px;
            }

            .torrent-search--grouped__download,
            .torrent-search--grouped__bookmark,
            .torrent-search--grouped__edit {
                text-align: center;
            }

            .torrent-search--grouped__type,
            .torrent-search--grouped__size,
            .torrent-search--grouped__seeders,
            .torrent-search--grouped__leechers,
            .torrent-search--grouped__completed,
            .torrent-search--grouped__age {
                text-align: right;
            }

            /* Styles for torrent information */
            th.torrent-search--grouped__type {
                grid-area: type;
                width: 7%;
            }

            td.torrent-search--grouped__overview {
                grid-area: overview;
                padding-right: 8px;
                display: grid;
                grid-template-columns: auto auto 1fr auto;
                grid-column-gap: 6px;
                align-items: center;
                grid-template-areas: 'edit name legend flags'
            }

            td.torrent-search--grouped__overview > div {
                display: contents;
            }

            .torrent-search--grouped__edit {
                grid-area: edit;
            }

            h3.torrent-search--grouped__name {
                grid-area: name;
                margin: 0;
                height: 100%;
            }

            h3.torrent-search--grouped__name a {
                line-height: 1.5;
                display: flex;
                height: 100%;
                align-items: center;
            }

            .torrent-search--grouped__legend {
                grid-area: legend;
                text-align: left;
            }

            .torrent-search--grouped__flags {
                grid-area: flags;
                letter-spacing: 3px;
            }

            td.torrent-search--grouped__download {
                grid-area: download;
                width: 2.5%;
            }

            td.torrent-search--grouped__bookmark {
                grid-area: bookmark;
                width: 2.5%;
            }

            td.torrent-search--grouped__size {
                grid-area: size;
                width: 8.5%;
            }

            td.torrent-search--grouped__seeders {
                grid-area: seeders;
                width: 4%;
            }

            td.torrent-search--grouped__leechers {
                grid-area: leechers;
                width: 4%;
            }

            td.torrent-search--grouped__completed {
                grid-area: completed;
                width: 4%;
            }

            td.torrent-search--grouped__age {
                grid-area: age;
                width: 12.5%;
            }

            td.torrent-search--grouped__age time {
                color: inherit;
            }

            @media screen and (max-width: 1024px) {
                /* Individual search result */
                td.torrent-search--grouped__size {
                    width: 11.5%;
                }
            }

            @media screen and (max-width: 767px) {

                /* Title header */
                .torrent-search--grouped__header {
                    grid-template-areas:
                        'poster title'
                        'poster directors'
                        'poster genres'
                        'poster plot';
                    grid-template-columns: auto 1fr;
                    grid-template-rows: auto;
                    height: 138px;
                }

                .torrent-search--grouped__poster img {
                    height: 138px;
                }

                .torrent-search--grouped__genres {
                    flex-direction: row;
                    justify-content: left;
                }


                .torrent-search--grouped__episode-dropdoiwn > summary,
                .torrent-search--grouped__season-dropdown > summary,
                .torrent-search--grouped__season-pack-dropdown > summary,
                .torrent-search--grouped__complete-pack-dropdown > summary {
                    padding-top: 8px;
                    padding-bottom: 8px;
                }

                /* Title's torrents */
                .torrent-search--grouped__movie-torrents,
                .torrent-search--grouped__episode-torrents,
                .torrent-search--grouped__season-pack-torrents,
                .torrent-search--grouped__complete-pack-torrents {
                    width: 100%;
                }

                .torrent-search--grouped__movie-torrents > tbody > tr,
                .torrent-search--grouped__episode-torrents > tbody > tr,
                .torrent-search--grouped__season-pack-torrents > tbody > tr,
                .torrent-search--grouped__complete-pack-torrents > tbody > tr {
                    display: grid;
                    grid-template-areas:
                        'type overview overview overview overview overview overview overview'
                        'type size age seeders leechers completed download bookmark';
                    grid-template-columns: 13% 1.5fr 1.5fr 1fr 1fr 1fr 7.5% 7.5%;
                }

                /* Buttons in table */
                .torrent-search--grouped__download a,
                .torrent-search--grouped__bookmark button,
                a.torrent-search--grouped__edit {
                    display: inline-grid;
                    place-items: center;
                }

                th.torrent-search--grouped__type,
                .torrent-search--grouped__edit,
                td.torrent-search--grouped__download,
                td.torrent-search--grouped__bookmark,
                td.torrent-search--grouped__size,
                td.torrent-search--grouped__seeders,
                td.torrent-search--grouped__leechers,
                td.torrent-search--grouped__completed,
                td.torrent-search--grouped__age {
                    align-self: center;
                    justify-self: center;
                    text-align: center;
                    width: 100%;
                    height: 100%;
                    display: grid;
                    place-items: center;
                }

                td.torrent-search--grouped__overview {
                    padding-top: 4px;
                }

                td.torrent-search--grouped__overview:not(tbody) {
                    border-top: 1px solid rgba(255, 255, 255, 0.03);
                }

                .torrent-search--grouped__name > a {
                    font-weight: bold;
                }
            }

            @media screen and (max-width: 480px) {
                /* Individual search result */
                .torrent-search--grouped__result {
                    margin: 12px 0;
                    border-radius: 0;
                }

                /* Title's torrents */
                .torrent-search--grouped__movie-torrents > tbody > tr,
                .torrent-search--grouped__episode-torrents > tbody > tr,
                .torrent-search--grouped__season-pack-torrents > tbody > tr,
                .torrent-search--grouped__complete-pack-torrents > tbody > tr {
                    grid-template-areas:
                        'edit name name name name name name name'
                        'type legend legend legend flags flags flags flags'
                        'type size size size age age age download'
                        'type . seeders leechers leechers completed . bookmark';
                    grid-template-columns: 13% 0.5fr 1fr 0.5fr 0.5fr 1fr 0.5fr 1fr;
                }

                td.torrent-search--grouped__overview,
                td.torrent-search--grouped__overview > div {
                    display: contents;
                }

                .torrent-search--grouped__edit:not(tr:first-child .torrent-search--grouped__edit),
                .torrent-search--grouped__name:not(tr:first-child .torrent-search--grouped__name) {
                    border-top: 1px solid var(--header-bg);
                }

                .torrent-search--grouped__name a {
                    padding: 12px 0;
                    display: inline-block;
                }

                .torrent-search--grouped__flags {
                    float: right;
                    text-align: right;
                }

                .torrent-search--grouped__size,
                .torrent-search--grouped__age {
                    text-align: center;
                }
            }
        </style>
        @foreach ($medias as $media)
            @php
                if ($media->category->movie_meta) {
                    $mediaType = 'movie';
                } elseif ($media->category->tv_meta) {
                    $mediaType = 'tv';
                } else {
                    $mediaType = 'no';
                }

                $meta = null;
                if ($media->category->movie_meta && $media->tmdb && $media->tmdb != 0 && $media->tmdb != '') {
                    $meta = \App\Models\Movie::with(['genres'])->find($media->tmdb);
                }
                if ($media->category->tv_meta && $media->tmdb && $media->tmdb != 0 && $media->tmdb != '') {
                    $meta = \App\Models\Tv::with(['genres'])->find($media->tmdb);
                }

                $media->torrents = \App\Models\Torrent::select(['id', 'name', 'size', 'seeders', 'leechers', 'times_completed',
                'category_id', 'type_id', 'resolution_id', 'season_number', 'episode_number', 'user_id', 'free',
                'doubleup', 'stream', 'highspeed', 'internal', 'sd', 'featured', 'anon', 'sticky', 'personal_release',
                'created_at', 'bumped_at', 'fl_until', 'du_until'])
                ->where('tmdb', '=', $media->tmdb)
                ->get();
            @endphp
            <article class="torrent-search--grouped__result">
                <header class="torrent-search--grouped__header" >
                    @if ($user->show_poster == 1)
                        <a
                                @switch($mediaType)
                                @case('movie')
                                @case('tv')
                                href="{{ route('torrents.similar', ['category_id' => $media->torrents->first()->category_id, 'tmdb' => $media->tmdb]) }}"
                                @endswitch
                                class="torrent-search--grouped__poster"
                        >
                            <img
                                    @switch($mediaType)
                                    @case ('movie')
                                    @case ('tv')
                                    src="{{ isset($meta->poster) ? tmdb_image('poster_small', $meta->poster) : 'https://via.placeholder.com/90x135' }}"
                                    @break
                                    @case ('game')
                                    src="{{ isset($meta->cover) ? 'https://images.igdb.com/igdb/image/upload/t_cover_small_2x/'.$meta->cover['image_id'].'.png' : 'https://via.placeholder.com/90x135' }}"
                                    @break
                                    @case ('no')
                                    @if(file_exists(public_path().'/files/img/torrent-cover_'.$media->torrents->first()->id.'.jpg'))
                                    src="{{ url('files/img/torrent-cover_'.$media->torrents->first()->id.'.jpg') }}"
                                    @else
                                    src="https://via.placeholder.com/90x135"
                                    @endif
                                    @break
                                    @default
                                    src="https://via.placeholder.com/90x135"
                                    @endswitch
                                    alt="{{ __('torrent.poster') }}"
                            >
                        </a>
                    @endif
                    <h2 class="torrent-search--grouped__title-name">
                        <a
                                href="{{ route('torrents.similar', ['category_id' => $media->torrents->first()->category_id, 'tmdb' => $media->tmdb]) }}"
                        >
                            @switch ($mediaType)
                                @case('movie')
                                {{ $meta->title ?? '' }} (<time>{{ \substr($meta->release_date, 0, 4) ?? '' }}</time>)
                                @break
                                @case('tv')
                                {{ $meta->name ?? '' }} (<time>{{ \substr($meta->first_air_date, 0, 4) ?? '' }}</time>)
                                @break
                            @endswitch
                        </a>
                    </h2>
                    <address class="torrent-search--grouped__directors">
                        @switch ($mediaType)
                            @case('movie')
                            @if(!empty($directors = (new App\Services\Tmdb\Client\Movie($media->tmdb))->get_crew()))
                                <span class="torrent-search-grouped__directors-by">by</span>
                                @foreach(collect($directors)->where('job', 'Director') as $director)
                                    <a href="{{ route('mediahub.persons.show', ['id' => $director['id']]) }}"
                                       class="torrent-search--grouped__director"
                                    >
                                        {{ $director['name'] }}
                                    </a>
                                    @if (! $loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                            @break
                            @case('tv')
                            @if(!empty($creators = (new App\Services\Tmdb\Client\TV($media->tmdb))->get_creator()))
                                <span class="torrent-search-grouped__directors-by">by</span>
                                @foreach($creators as $creator)
                                    <a href="{{ route('mediahub.persons.show', ['id' => $creator['id']]) }}"
                                       class="torrent-search--grouped__director"
                                    >
                                        {{ $creator['name'] }}
                                    </a>
                                    @if (! $loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                            @break
                        @endswitch
                    </address>
                    <div class="torrent-search--grouped__genres">
                        @if (isset($meta->genres) && $meta->genres->isNotEmpty())
                            @foreach ($meta->genres->take(3) as $genre)
                                <a
                                    href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}"
                                    class="torrent-search--grouped__genre"
                                >
                                    {{ $genre->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <p class="torrent-search--grouped__plot">
                        @switch (true)
                            @case($mediaType === 'movie')
                            @case($mediaType === 'tv')
                            {{ $meta->overview }}
                            @break
                        @endswitch
                    </p>
                </header>
                <section>
                    @switch ($mediaType)
                        @case('movie')
                        <table class="torrent-search--grouped__movie-torrents">
                            @foreach ($media->torrents->sortBy('type.position')->values()->groupBy('type_id') as $torrentsByType)
                                <tbody>
                                @foreach ($torrentsByType->sortBy([['resolution.position', 'asc'], ['internal', 'desc'], ['size', 'desc']]) as $torrent)
                                    <tr>
                                        @if ($loop->first)
                                            <th
                                                    class="torrent-search--grouped__type"
                                                    scope="rowgroup"
                                                    rowspan="{{ $loop->count }}"
                                            >
                                                {{ $torrent->type->name }}
                                            </th>
                                        @endif
                                        @include('livewire.includes._torrent-group-row')
                                    </tr>
                                @endforeach
                                </tbody>
                            @endforeach
                        </table>
                        @break
                        @case('tv')
                        @foreach($media->torrents->groupBy('season_number')->sortKeys() as $season_number => $season)
                            @if ($season_number === 0)
                                @foreach ($season->groupBy('episode_number')->sortKeys() as $episode_number => $episode)
                                    @if ($episode_number === 0)
                                        <details class="torrent-search--grouped__complete-pack-dropdown" open>
                                            <summary>Complete Pack</summary>
                                            <table class="torrent-search--grouped__complete-pack-torrents">
                                                @foreach ($episode->sortBy('type.position')->groupBy('type_id') as $torrentsByType)
                                                    <tbody>
                                                    @foreach ($torrentsByType as $torrent)
                                                        <tr>
                                                            @if ($loop->first)
                                                                <th
                                                                        class="torrent-search--grouped__type"
                                                                        scope="rowgroup"
                                                                        rowspan="{{ $loop->count }}"
                                                                >
                                                                    {{ $torrent->type->name }}
                                                                </th>
                                                            @endif
                                                            @include('livewire.includes._torrent-group-row')
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                @endforeach
                                            </table>
                                        </details>
                                    @endif
                                @endforeach
                            @else
                                <details
                                        class="torrent-search--grouped__season-dropdown"
                                        @if ($loop->last)
                                        open
                                        @endif
                                >
                                    <summary>Season {{ $season_number }}</summary>
                                    @foreach ($season->groupBy('episode_number')->sortKeys() as $episode_number => $episode)
                                        <details
                                                @if ($episode_number === 0)
                                                class="torrent-search--grouped__season-pack-dropdown"
                                                open
                                                @else
                                                class="torrent-search--grouped__episode-dropdown"
                                                @endif
                                        >
                                            <summary>
                                                @if ($episode_number === 0)
                                                    Season Pack
                                                @else
                                                    Episode {{ $episode_number }}
                                                @endif
                                            </summary>
                                            <table
                                                    @if ($episode_number == 0)
                                                    class="torrent-search--grouped__season-pack-torrents"
                                                    @else
                                                    class="torrent-search--grouped__episode-torrents"
                                                    @endif
                                            >
                                                @foreach ($episode->sortBy('type.position')->groupBy('type_id') as $torrentsByType)
                                                    <tbody>
                                                    @foreach ($torrentsByType->filter(fn ($torrent) => !($torrent->episode_number === 0 && $torrent->season_number === 0))->sortBy([['resolution.position', 'asc'], ['internal', 'desc'], ['size', 'desc']]) as $torrent)
                                                        <tr>
                                                            @if ($loop->first)
                                                                <th
                                                                        class="torrent-search--grouped__type"
                                                                        scope="rowgroup"
                                                                        rowspan="{{ $loop->count }}"
                                                                >
                                                                    {{ $torrent->type->name }}
                                                                </th>
                                                            @endif
                                                            @include('livewire.includes._torrent-group-row')
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                @endforeach
                                            </table>
                                        </details>
                                    @endforeach
                                </details>
                            @endif
                        @endforeach
                        @break
                    @endswitch
                </section>
            </article>
        @endforeach

        @if (! $medias->count())
            <div class="margin-10 torrent-listings-no-result">
                {{ __('common.no-result') }}
            </div>
        @endif
        <br>
        <div class="text-center torrent-listings-pagination">
            {{ $medias->links() }}
        </div>
        <br>
    </div>
</div>

<script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
  document.addEventListener('livewire:load', function () {
    let myOptions = [
            @foreach($regions as $region)
      {
        label: "{{ $region->name }}", value: "{{ $region->id }}"
      },
        @endforeach
    ]
    VirtualSelect.init({
      ele: '#regions',
      options: myOptions,
      multiple: true,
      search: true,
      placeholder: "{{__('Select Regions')}}",
      noOptionsText: "{{__('No results found')}}",
    })

    let regions = document.querySelector('#regions')
    regions.addEventListener('change', () => {
      let data = regions.value
    @this.set('regions', data)
    })

    let myOptions2 = [
            @foreach($distributors as $distributor)
      {
        label: "{{ $distributor->name }}", value: "{{ $distributor->id }}"
      },
        @endforeach
    ]
    VirtualSelect.init({
      ele: '#distributors',
      options: myOptions2,
      multiple: true,
      search: true,
      placeholder: "{{__('Select Distributor')}}",
      noOptionsText: "{{__('No results found')}}",
    })

    let distributors = document.querySelector('#distributors')
    distributors.addEventListener('change', () => {
      let data = distributors.value
    @this.set('distributors', data)
    })
  })
</script>
