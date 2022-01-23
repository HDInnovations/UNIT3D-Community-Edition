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
                                x-text="open ? '{{ __('common.search-hide') }}' : '{{ __('common.search-advanced') }}'"></button>
                    </div>
                </div>
                <div x-show="open" id="torrent-advanced-search">
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
                            @foreach (App\Models\Genre::all()->sortBy('name') as $genre)
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
									<input wire:model.prefetch="free0" type="checkbox" value="0">
									0% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free25" type="checkbox" value="25">
									25% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free50" type="checkbox" value="50">
									50% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free75" type="checkbox" value="75">
									75% Freeleech
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model.prefetch="free100" type="checkbox" value="100">
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
        {{ $torrents->links() }}
    </div>
    <br>
    <div class="table-responsive block">
			<span class="badge-user torrent-listings-stats" style="float: right;">
				<strong>Total:</strong> {{ number_format($torrentsStat->total) }} |
				<strong>Alive:</strong> {{ number_format($torrentsStat->alive) }} |
				<strong>Dead:</strong> {{ number_format($torrentsStat->dead) }} |
			</span>
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
            <a href="{{ route('torrents') }}" class="btn btn-xs btn-primary">
                <i class="{{ config('other.font-awesome') }} fa-list"></i> {{ __('torrent.list') }}
            </a>
            <a href="{{ route('cards') }}" class="btn btn-xs btn-primary">
                <i class="{{ config('other.font-awesome') }} fa-image"></i> {{ __('torrent.cards') }}
            </a>
            <a href="#" class="btn btn-xs btn-primary">
                <i class="{{ config('other.font-awesome') }} fa-clone"></i> {{ __('torrent.groupings') }}
            </a>
            <a href="{{ route('rss.index') }}" class="btn btn-xs btn-warning">
                <i class="{{ config('other.font-awesome') }} fa-rss"></i> {{ __('rss.rss') }} {{ __('rss.feeds') }}
            </a>
        </div>
        <table class="table table-condensed table-striped table-bordered" id="torrent-list-table">
            <thead>
            <tr>
                <th class="torrent-listings-poster"></th>
                <th class="torrent-listings-format"></th>
                <th class="torrents-filename torrent-listings-overview">
                    <div sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null"
                         role="button">
                        {{ __('common.name') }}
                        @include('livewire.includes._sort-icon', ['field' => 'name'])
                    </div>
                </th>
                <th class="torrent-listings-download">
                    <div>
                        <i class="{{ config('other.font-awesome') }} fa-download"></i>
                    </div>
                </th>
                <th class="torrent-listings-tmdb">
                    <div>
                        <i class="{{ config('other.font-awesome') }} fa-id-badge"></i>
                    </div>
                </th>
                <th class="torrent-listings-size">
                    <div sortable wire:click="sortBy('size')" :direction="$sortField === 'size' ? $sortDirection : null"
                         role="button">
                        <i class="{{ config('other.font-awesome') }} fa-database"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'size'])
                    </div>
                </th>
                <th class="torrent-listings-seeders">
                    <div sortable wire:click="sortBy('seeders')"
                         :direction="$sortField === 'seeders' ? $sortDirection : null" role="button">
                        <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-up"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                    </div>
                </th>
                <th class="torrent-listings-leechers">
                    <div sortable wire:click="sortBy('leechers')"
                         :direction="$sortField === 'leechers' ? $sortDirection : null" role="button">
                        <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-down"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                    </div>
                </th>
                <th class="torrent-listings-completed">
                    <div sortable wire:click="sortBy('times_completed')"
                         :direction="$sortField === 'times_completed' ? $sortDirection : null" role="button">
                        <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                        @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                    </div>
                </th>
                <th class="torrent-listings-age">
                    <div sortable wire:click="sortBy('created_at')"
                         :direction="$sortField === 'created_at' ? $sortDirection : null" role="button">
                        {{ __('common.created_at') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($torrents as $torrent)
                @php $meta = null @endphp
                @if ($torrent->category->tv_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
                        @php $meta = cache()->remember('tvmeta:'.$torrent->tmdb.$torrent->category_id, 3_600, fn () => App\Models\Tv::select(['id', 'poster', 'vote_average'])->where('id', '=', $torrent->tmdb)->first()) @endphp
                    @endif
                @endif
                @if ($torrent->category->movie_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
                        @php $meta = cache()->remember('moviemeta:'.$torrent->tmdb.$torrent->category_id, 3_600, fn () => App\Models\Movie::select(['id', 'poster', 'vote_average'])->where('id', '=', $torrent->tmdb)->first()) @endphp
                    @endif
                @endif
                @if ($torrent->category->game_meta)
                    @if ($torrent->igdb || $torrent->igdb != 0)
                        @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                    @endif
                @endif

                @if ($torrent->sticky == 1)
                    <tr class="success">
                @else
                    <tr>
                        @endif
                        <td class="torrent-listings-poster" style="width: 1%;">
                            @if ($user->show_poster == 1)
                                <div class="torrent-poster pull-left">
                                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                                        <img src="{{ isset($meta->poster) ? tmdb_image('poster_small', $meta->poster) : 'https://via.placeholder.com/90x135' }}"
                                             class="torrent-poster-img-small" alt="{{ __('torrent.poster') }}">
                                    @endif

                                    @if ($torrent->category->game_meta)
                                        <img style="height: 80px;"
                                             src="{{ isset($meta->cover) ? 'https://images.igdb.com/igdb/image/upload/t_cover_small_2x/'.$meta->cover['image_id'].'.png' : 'https://via.placeholder.com/90x135' }}"
                                             class="torrent-poster-img-small" alt="{{ __('torrent.poster') }}">
                                    @endif

                                    @if ($torrent->category->music_meta)
                                        <img src="https://via.placeholder.com/90x135" class="torrent-poster-img-small"
                                             alt="{{ __('torrent.poster') }}">
                                    @endif

                                    @if ($torrent->category->no_meta)
                                        @if(file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                                            <img src="{{ url('files/img/torrent-cover_' . $torrent->id . '.jpg') }}"
                                                 class="torrent-poster-img-small" alt="{{ __('torrent.poster') }}">
                                        @else
                                            <img src="https://via.placeholder.com/400x600"
                                                 class="torrent-poster-img-small" alt="{{ __('torrent.poster') }}">
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div class="torrent-poster pull-left"></div>
                        @endif
                        <td class="torrent-listings-format" style="width: 5%; text-align: center;">
                            <div class="text-center">
                                <i class="{{ $torrent->category->icon }} torrent-icon"
                                   style="@if ($torrent->category->movie_meta || $torrent->category->tv_meta) padding-top: 1px; @else padding-top: 15px; @endif font-size: 24px;"></i>
                            </div>
                            <div class="text-center">
                                <span class="label label-success" style="font-size: 13px">
                                    {{ $torrent->type->name }}
                                </span>
                            </div>
                            @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                                <div class="text-center" style="padding-top: 5px;">
                                <span class="label label-success" style="font-size: 13px">
                                    {{ $torrent->resolution->name ?? 'N/A' }}
                                </span>
                                </div>
                            @endif
                        </td>
                        <td class="torrent-listings-overview" style="vertical-align: middle;">
                            @if($user->group->is_modo || $user->id === $torrent->user_id)
                                <a href="{{ route('edit_form', ['id' => $torrent->id]) }}">
                                    <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                            data-original-title="{{ __('common.edit') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i>
                                    </button>
                                </a>
                            @endif
                            <a class="view-torrent torrent-listings-name" style="font-size: 16px;"
                               href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                {{ $torrent->name }}
                            </a>
                            @if ($current = $user->history->where('info_hash', $torrent->info_hash)->first())
                                @if ($current->seeder == 1 && $current->active == 1)
                                    <button class="btn btn-success btn-circle torrent-listings-seeding" type="button"
                                            data-toggle="tooltip"
                                            data-original-title="{{ __('torrent.currently-seeding') }}!">
                                        <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                    </button>
                                @endif

                                @if ($current->seeder == 0 && $current->active == 1)
                                    <button class="btn btn-warning btn-circle torrent-listings-leeching" type="button"
                                            data-toggle="tooltip"
                                            data-original-title="{{ __('torrent.currently-leeching') }}!">
                                        <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                    </button>
                                @endif

                                @if ($current->seeder == 0 && $current->active == 0 && $current->completed_at == null)
                                    <button class="btn btn-info btn-circle torrent-listings-incomplete" type="button"
                                            data-toggle="tooltip"
                                            data-original-title="{{ __('torrent.not-completed') }}!">
                                        <i class="{{ config('other.font-awesome') }} fa-spinner"></i>
                                    </button>
                                @endif

                                @if ($current->seeder == 1 && $current->active == 0 && $current->completed_at != null)
                                    <button class="btn btn-danger btn-circle torrent-listings-complete" type="button"
                                            data-toggle="tooltip"
                                            data-original-title="{{ __('torrent.completed-not-seeding') }}!">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                    </button>
                                @endif
                            @endif
                            <br>
                            @if ($torrent->anon === 0)
                                <span class="badge-extra torrent-listings-uploader">
									<i class="{{ config('other.font-awesome') }} {{ $torrent->user->group->icon }}"></i>
                                    <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                        {{ $torrent->user->username }}
                                    </a>
                                </span>
                            @else
                                <span class="badge-extra torrent-listings-uploader">
									<i class="{{ config('other.font-awesome') }} fa-ghost"></i>
									{{ strtoupper(__('common.anonymous')) }}
                                    @if ($user->group->is_modo || $torrent->user->username === $user->username)
                                        <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                            ({{ $torrent->user->username }})
                                        </a>
                                    @endif
                                </span>
                            @endif
                            <span class='badge-extra text-pink torrent-listings-thanks'>
                                    <i class="{{ config('other.font-awesome') }} fa-heartbeat"></i> {{ $torrent->thanks_count }}
                                </span>
                            <span class='badge-extra text-green torrent-listings-comments'>
									<i class="{{ config('other.font-awesome') }} fa-comment-alt-lines"></i> {{ $torrent->comments_count }}
								</span>
                            @if ($torrent->internal == 1)
                                <span class='badge-extra text-bold torrent-listings-internal'>
                                    <i class='{{ config('other.font-awesome') }} fa-magic' data-toggle='tooltip'
                                       title=''
                                       data-original-title='{{ __('torrent.internal-release') }}'
                                       style="color: #baaf92;"></i>
                                </span>
                            @endif

                            @if ($torrent->personal_release == 1)
                                <span class='badge-extra text-bold torrent-listings-personal'>
                                    <i class='{{ config('other.font-awesome') }} fa-user-plus' data-toggle='tooltip'
                                       title=''
                                       data-original-title='Personal Release' style="color: #865be9;"></i>
                                </span>
                            @endif

                            @if ($torrent->stream == 1)
                                <span class='badge-extra text-bold torrent-listings-stream-optimized'>
                                    <i class='{{ config('other.font-awesome') }} fa-play text-red' data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.stream-optimized') }}'></i>
                                </span>
                            @endif

                            @if ($torrent->featured == 0)
                                @if ($torrent->doubleup == 1)
                                    <span class='badge-extra text-bold torrent-listings-double-upload'>
                                        <i class='{{ config('other.font-awesome') }} fa-gem text-green'
                                           data-toggle='tooltip'
                                           title='' data-original-title='{{ __('torrent.double-upload') }}'></i>
                                    </span>
                                @endif

                                @if ($torrent->free >= '90')
                                    <span class="badge-extra text-bold torrent-listings-freeleech" data-toggle="tooltip"
                                          title='' data-original-title='{{ $torrent->free }}% {{ __('common.free') }}'>
                                            <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                        </span>
                                @elseif ($torrent->free < '90' && $torrent->free >= '30')
                                    <style>
                                        .star50 {
                                            position: relative;
                                        }

                                        .star50:after {
                                            content: "\f005";
                                            position: absolute;
                                            left: 0;
                                            top: 0;
                                            width: 50%;
                                            overflow: hidden;
                                            color: #FFB800;
                                        }
                                    </style>
                                    <span class="badge-extra text-bold torrent-listings-freeleech" data-toggle="tooltip"
                                          title='' data-original-title='{{ $torrent->free }}% {{ __('common.free') }}'>
                                            <i class="star50 {{ config('other.font-awesome') }} fa-star"></i>
                                        </span>
                                @elseif ($torrent->free < '30' && $torrent->free != '0')
                                    <style>
                                        .star30 {
                                            position: relative;
                                        }

                                        .star30:after {
                                            content: "\f005";
                                            position: absolute;
                                            left: 0;
                                            top: 0;
                                            width: 30%;
                                            overflow: hidden;
                                            color: #FFB800;
                                        }
                                    </style>
                                    <span class="badge-extra text-bold torrent-listings-freeleech" data-toggle="tooltip"
                                          title='' data-original-title='{{ $torrent->free }}% {{ __('common.free') }}'>
                                            <i class="star30 {{ config('other.font-awesome') }} fa-star"></i>
                                        </span>
                                @endif
                            @endif

                            @if ($personalFreeleech)
                                <span class='badge-extra text-bold torrent-listings-personal-freeleech'>
                                    <i class='{{ config('other.font-awesome') }} fa-id-badge text-orange'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.personal-freeleech') }}'></i>
                                </span>
                            @endif

                            @if ($user->freeleechTokens->where('torrent_id', $torrent->id)->first())
                                <span class='badge-extra text-bold torrent-listings-freeleech-token'>
                                    <i class='{{ config('other.font-awesome') }} fa-star text-bold'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.freeleech-token') }}'></i>
                                </span>
                            @endif

                            @if ($torrent->featured == 1)
                                <span class='badge-extra text-bold torrent-listings-featured'
                                      style='background-image:url(/img/sparkels.gif);'>
                                    <i class='{{ config('other.font-awesome') }} fa-certificate text-pink'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.featured') }}'></i>
                                </span>
                            @endif

                            @if ($user->group->is_freeleech == 1)
                                <span class='badge-extra text-bold torrent-listings-special-freeleech'>
                                    <i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.special-freeleech') }}'></i>
                                </span>
                            @endif

                            @if (config('other.freeleech') == 1)
                                <span class='badge-extra text-bold torrent-listings-global-freeleech'>
                                    <i class='{{ config('other.font-awesome') }} fa-globe text-blue'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.global-freeleech') }}'></i>
                                </span>
                            @endif

                            @if (config('other.doubleup') == 1)
                                <span class='badge-extra text-bold torrent-listings-global-double-upload'>
                                    <i class='{{ config('other.font-awesome') }} fa-globe text-green'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.global-double-upload') }}'></i>
                                </span>
                            @endif

                            @if ($user->group->is_double_upload == 1)
                                <span class='badge-extra text-bold torrent-listings-special-double-upload'>
									<i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                       data-toggle='tooltip' title=''
                                       data-original-title='{{ __('torrent.special-double_upload') }}'></i>
								</span>
                            @endif

                            @if ($torrent->leechers >= 5)
                                <span class='badge-extra text-bold torrent-listings-hot'>
                                    <i class='{{ config('other.font-awesome') }} fa-fire text-orange'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('common.hot') }}'></i>
                                </span>
                            @endif

                            @if ($torrent->sticky == 1)
                                <span class='badge-extra text-bold torrent-listings-sticky'>
                                    <i class='{{ config('other.font-awesome') }} fa-thumbtack text-black'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.sticky') }}'></i>
                                </span>
                            @endif

                            @if ($torrent->highspeed == 1)
                                <span class='badge-extra text-bold torrent-listings-high-speed'>
									<i class='{{ config('other.font-awesome') }} fa-tachometer text-red'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('common.high-speeds') }}'></i>
								</span>
                            @endif

                            @if ($torrent->sd == 1)
                                <span class='badge-extra text-bold torrent-listings-sd'>
									<i class='{{ config('other.font-awesome') }} fa-ticket text-orange'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.sd-content') }}'></i>
								</span>
                            @endif

                            @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Carbon\Carbon::now()->addDay(2))
                                <span class='badge-extra text-bold torrent-listings-bumped'>
                                    <i class='{{ config('other.font-awesome') }} fa-level-up-alt text-gold'
                                       data-toggle='tooltip'
                                       title='' data-original-title='{{ __('torrent.recent-bumped') }}'></i>
                                </span>
                            @endif
                        </td>
                        <td class="torrent-listings-download" style="vertical-align: middle;">
                            @if (config('torrent.download_check_page') == 1)
                                <a href="{{ route('download_check', ['id' => $torrent->id]) }}">
                                    <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                            data-original-title="{{ __('common.download') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                    </button>
                                </a>
                            @else
                                <a href="{{ route('download', ['id' => $torrent->id]) }}">
                                    <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                            data-original-title="{{ __('common.download') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                    </button>
                                </a>
                            @endif
                            @if (config('torrent.magnet') == 1)
                                <a href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => $user->rsskey ]) }}&tr={{ route('announce', ['passkey' => $user->passkey]) }}&xl={{ $torrent->size }}">
                                    <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                            data-original-title="{{ __('common.magnet') }}">
                                        <i class="{{ config('other.font-awesome') }} fa-magnet"></i>
                                    </button>
                                </a>
                            @endif
                            <div>
                                @livewire('small-bookmark-button', ['torrent' => $torrent->id], key($torrent->id))
                            </div>
                        </td>
                        <td class="torrent-listings-tmdb" style="vertical-align: middle;">
                            @if ($torrent->category->game_meta)
                                <span class='badge-extra'>
										<img src="{{ url('img/igdb.png') }}" alt="igdb_id" style="margin-left: -5px;"
                                             width="24px" height="24px"> {{ $torrent->igdb }}
	                                    <br>
										<span class="{{ rating_color($meta->rating ?? 'text-white') }}"><i
                                                    class="{{ config('other.font-awesome') }} fa-star-half-alt"></i> {{ round($meta->rating ?? 0) }}/100 </span>
                                    </span>
                            @endif
                            @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                                <span class='badge-extra'>
	                                    <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $torrent->tmdb]) }}">
											<img src="{{ url('img/tmdb_small.png') }}" alt="tmdb_id"
                                                 style="margin-left: -5px;" width="24px" height="24px"> {{ $torrent->tmdb }}
	                                    </a>
	                                    <br>
										<span class="{{ rating_color($meta->vote_average ?? 'text-white') }}"><i
                                                    class="{{ config('other.font-awesome') }} fa-star-half-alt"></i> {{ $meta->vote_average ?? 0 }}/10 </span>
                                    </span>
                            @endif
                        </td>
                        <td class="torrent-listings-size" style="vertical-align: middle;">
                            <span class='badge-extra'>
                                {{ $torrent->getSize() }}
                            </span>
                        </td>
                        <td class="torrent-listings-seeders" style="vertical-align: middle;">
                            <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                    <span class='badge-extra text-green'>
	                                    {{ $torrent->seeders }}
                                    </span>
                            </a>
                        </td>
                        <td class="torrent-listings-leechers" style="vertical-align: middle;">
                            <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                                    <span class='badge-extra text-red'>
	                                    {{ $torrent->leechers }}
                                    </span>
                            </a>
                        </td>
                        <td class="torrent-listings-completed" style="vertical-align: middle;">
                            <a href="{{ route('history', ['id' => $torrent->id]) }}">
                                    <span class='badge-extra text-orange'>
	                                    {{ $torrent->times_completed }}
                                    </span>
                            </a>
                        </td>
                        <td class="torrent-listings-age" style="vertical-align: middle;">
							<span class='badge-extra'>
								{{ $torrent->created_at->diffForHumans() }}
							</span>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
        @if (! $torrents->count())
            <div class="margin-10 torrent-listings-no-result">
                {{ __('common.no-result') }}
            </div>
        @endif
        <br>
        <div class="text-center torrent-listings-pagination">
            {{ $torrents->links() }}
        </div>
        <br>
        <div class="container-fluid well torrent-listings-legend">
            <div class="text-center">
                <strong>{{ __('common.legend') }}:</strong>
                <button class='btn btn-success btn-circle torrent-listings-seeding' type='button' data-toggle='tooltip'
                        title=''
                        data-original-title='{{ __('torrent.currently-seeding') }}!'>
                    <i class='{{ config('other.font-awesome') }} fa-arrow-up'></i>
                </button>
                <button class='btn btn-warning btn-circle torrent-listings-leeching' type='button' data-toggle='tooltip'
                        title=''
                        data-original-title='{{ __('torrent.currently-leeching') }}!'>
                    <i class='{{ config('other.font-awesome') }} fa-arrow-down'></i>
                </button>
                <button class='btn btn-info btn-circle torrent-listings-incomplete' type='button' data-toggle='tooltip'
                        title=''
                        data-original-title='{{ __('torrent.not-completed') }}!'>
                    <i class='{{ config('other.font-awesome') }} fa-spinner'></i>
                </button>
                <button class='btn btn-danger btn-circle torrent-listings-complete' type='button' data-toggle='tooltip'
                        title=''
                        data-original-title='{{ __('torrent.completed-not-seeding') }}!'>
                    <i class='{{ config('other.font-awesome') }} fa-thumbs-down'></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
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
