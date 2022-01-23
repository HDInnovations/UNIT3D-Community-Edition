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
                        <input wire:model="name" type="search" class="form-control" placeholder="Name"/>
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
                            <input wire:model="description" type="text" class="form-control" placeholder="Description">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-mediainfo">
                            <label for="mediainfo" class="label label-default">{{ __('torrent.media-info') }}</label>
                            <input wire:model="mediainfo" type="text" class="form-control" placeholder="Mediainfo">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-keywords">
                            <label for="keywords" class="label label-default">{{ __('torrent.keywords') }}</label>
                            <input wire:model="keywords" type="text" class="form-control" placeholder="Keywords">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-uploader">
                            <label for="uploader" class="label label-default">{{ __('torrent.uploader') }}</label>
                            <input wire:model="uploader" type="text" class="form-control" placeholder="Uploader">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3 col-xs-6 adv-search-tmdb">
                            <label for="tmdbId" class="label label-default">TMDb</label>
                            <input wire:model="tmdbId" type="text" class="form-control" placeholder="TMDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-imdb">
                            <label for="imdbId" class="label label-default">IMDb</label>
                            <input wire:model="imdbId" type="text" class="form-control" placeholder="IMDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-tvdb">
                            <label for="tvdbId" class="label label-default">TVDb</label>
                            <input wire:model="tvdbId" type="text" class="form-control" placeholder="TVDb ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-mal">
                            <label for="malId" class="label label-default">MAL</label>
                            <input wire:model="malId" type="text" class="form-control" placeholder="MAL ID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-3 col-xs-6 adv-search-startYear">
                            <label for="startYear" class="label label-default">{{ __('torrent.start-year') }}</label>
                            <input wire:model="startYear" type="text" class="form-control" placeholder="Start Year">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-endYear">
                            <label for="endYear" class="label label-default">{{ __('torrent.end-year') }}</label>
                            <input wire:model="endYear" type="text" class="form-control" placeholder="End Year">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-playlist">
                            <label for="playlist" class="label label-default">Playlist</label>
                            <input wire:model="playlistId" type="text" class="form-control" placeholder="Playlist ID">
                        </div>
                        <div class="form-group col-sm-3 col-xs-6 adv-search-collection">
                            <label for="collection" class="label label-default">Collection</label>
                            <input wire:model="collectionId" type="text" class="form-control"
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
										<input type="checkbox" wire:model="categories" value="{{ $category->id }}"> {{ $category->name }}
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
										<input type="checkbox" wire:model="types" value="{{ $type->id }}"> {{ $type->name }}
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
										<input type="checkbox" wire:model="resolutions" value="{{ $resolution->id }}"> {{ $resolution->name }}
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
										<input type="checkbox" wire:model="genres" value="{{ $genre->id }}"> {{ $genre->name }}
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
									<input wire:model="doubleup" type="checkbox" value="1">
									Double Upload
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="featured" type="checkbox" value="1">
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
									<input wire:model="stream" type="checkbox" value="1">
									Stream Optimized
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="sd" type="checkbox" value="1">
									SD Content
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="highspeed" type="checkbox" value="1">
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
									<input wire:model="internal" type="checkbox" value="1">
									Internal
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="personalRelease" type="checkbox" value="1">
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
									<input wire:model="bookmarked" type="checkbox" value="1">
									Bookmarked
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="wished" type="checkbox" value="1">
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
									<input wire:model="alive" type="checkbox" value="1">
									{{ __('torrent.alive') }}
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="dying" type="checkbox" value="1">
									{{ __('torrent.dying-torrent') }}
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="dead" type="checkbox" value="1">
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
									<input wire:model="notDownloaded" type="checkbox" value="1">
									Not Downloaded
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="downloaded" type="checkbox" value="1">
									Downloaded
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="seeding" type="checkbox" value="1">
									Seeding
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="leeching" type="checkbox" value="1">
									Leeching
								</label>
							</span>
                            <span class="badge-user">
								<label class="inline">
									<input wire:model="incomplete" type="checkbox" value="1">
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
									<option value="25">24</option>
									<option value="50">48</option>
									<option value="100">72</option>
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
        </table>
        @foreach($torrents as $torrent)
            @php $meta = null @endphp
            @if ($torrent->category->tv_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php
                        $meta = cache()->remember('tv.'.$torrent->tmdb, 3_600, function() use ($torrent) {
                            return App\Models\Tv::where('id', '=', $torrent->tmdb)->first();
                        })
                    @endphp
                @endif
            @endif
            @if ($torrent->category->movie_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php
                        $meta = cache()->remember('movie.'.$torrent->tmdb, 3_600, function() use ($torrent) {
                            return App\Models\Movie::where('id', '=', $torrent->tmdb)->first();
                        })
                    @endphp
                @endif
            @endif
            @if ($torrent->category->game_meta)
                @if ($torrent->igdb || $torrent->igdb != 0)
                    @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                @endif
            @endif

            <div class="col-md-4">
                <div class="card is-torrent">
                    <div class="card_head">
							<span class="badge-user text-bold" style="float:right;">
								<i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i>
								{{ $torrent->seeders }} /
								<i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i>
								{{ $torrent->leechers }} /
								<i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>
								{{ $torrent->times_completed }}
							</span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">
								{{ $torrent->getSize() }}
							</span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">
								{{ $torrent->resolution->name ?? 'No Res' }}
							</span>
                        <span class="badge-user text-bold text-blue" style="float:right;">
								{{ $torrent->type->name }}
							</span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">
								{{ $torrent->category->name }}
							</span>&nbsp;
                    </div>
                    <div class="card_body">
                        <div class="body_poster">
                            @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                                <img src="{{ isset($meta->poster) ? tmdb_image('poster_mid', $meta->poster) : 'https://via.placeholder.com/200x300' }}"
                                     class="show-poster" alt="{{ __('torrent.poster') }}">
                            @endif

                            @if ($torrent->category->game_meta && isset($meta) && $meta->cover->image_id && $meta->name)
                                <img src="https://images.igdb.com/igdb/image/upload/t_cover_big/{{ $meta->cover->image_id }}.jpg"
                                     class="show-poster"
                                     data-name='<i style="color: #a5a5a5;">{{ $meta->name ?? 'N/A' }}</i>'
                                     data-image='<img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover->image_id }}.jpg"
									     alt="{{ __('torrent.poster') }}" style="height: 1000px;">'
                                     class="torrent-poster-img-small show-poster" alt="{{ __('torrent.poster') }}">
                            @endif

                            @if ($torrent->category->music_meta)
                                <img src="https://via.placeholder.com/200x300" class="show-poster"
                                     data-name='<i style="color: #a5a5a5;">N/A</i>'
                                     data-image='<img src="https://via.placeholder.com/200x300"
									     alt="{{ __('torrent.poster') }}" style="height: 1000px;">'
                                     class="torrent-poster-img-small show-poster" alt="{{ __('torrent.poster') }}">
                            @endif

                            @if ($torrent->category->no_meta)
                                @if(file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                                    <img src="{{ url('files/img/torrent-cover_' . $torrent->id . '.jpg') }}"
                                         class="show-poster" alt="{{ __('torrent.poster') }}">
                                @else
                                    <img src="https://via.placeholder.com/200x300" class="show-poster"
                                         data-name='<i style="color: #a5a5a5;">N/A</i>'
                                         data-image='<img src="https://via.placeholder.com/200x300" alt="{{ __('torrent.poster') }}" style="height: 1000px;">'
                                         class="torrent-poster-img-small show-poster" alt="{{ __('torrent.poster') }}">
                                @endif
                            @endif
                        </div>
                        <div class="body_description">
                            <h3 class="description_title">
                                <a href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                    {{ $torrent->name }}
                                </a>
                            </h3>
                            @if (isset($meta->genres) && $meta->genres->isNotEmpty())
                                @foreach ($meta->genres as $genre)
                                    <span class="genre-label">
                                            <a href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}">
                                                <i class="{{ config('other.font-awesome') }} fa-theater-masks"></i> {{ $genre->name }}
                                            </a>
                                        </span>
                                @endforeach
                            @endif
                            <p class="description_plot">
                                @if($torrent->category->movie_meta || $torrent->category->tv_meta)
                                    {{ $meta->overview ?? '' }}
                                @endif

                                @if($torrent->category->game_meta)
                                    {{ $meta->summary ?? '' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="card_footer">
                        <div style="float: left;">
                            @if ($torrent->anon == 1)
                                <span class="badge-user text-orange text-bold">{{ strtoupper(__('common.anonymous')) }}
                                    @if ($user->id === $torrent->user->id || $user->group->is_modo)
                                        <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
												({{ $torrent->user->username }})
											</a>
                                    @endif
									</span>
                            @else
                                <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
										<span class="badge-user text-bold"
                                              style="color:{{ $torrent->user->group->color }}; background-image:{{ $torrent->user->group->effect }};">
											<i class="{{ $torrent->user->group->icon }}" data-toggle="tooltip"
                                               data-original-title="{{ $torrent->user->group->name }}"></i>
											{{ $torrent->user->username }}
										</span>
                                </a>
                            @endif
                        </div>
                        <span class="badge-user text-bold" style="float: right;">
								<i class="{{ config('other.font-awesome') }} fa-thumbs-up text-gold"></i>
								@if($torrent->category->movie_meta || $torrent->category->tv_meta)
                                {{ round($meta->vote_average ?? 0) }}/10
                                ({{ $meta->vote_count ?? 0 }} {{ __('torrent.votes') }})
                            @endif
                            @if($torrent->category->game_meta)
                                {{ round($meta->rating ?? 0) }}/100
                                ({{ $meta->rating_count ?? 0 }} {{ __('torrent.votes') }})
                            @endif
							</span>
                    </div>
                </div>
            </div>
        @endforeach
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

