<div class="container-fluid">
    @include('torrent.partials.search')
    <br>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <div class="form__group">
                        <select
                                class="form__select"
                                wire:model="perPage"
                                required
                        >
                            <option>25</option>
                            <option>50</option>
                            <option>75</option>
                            <option>100</option>
                        </select>
                        <label class="form__label form__label--floating">
                            {{ __('common.quantity') }}
                        </label>
                    </div>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            {{ $torrents->links('partials.pagination') }}
            <table class="data-table">
                <thead>
                <tr>
                    <th class="torrent-listings-poster"></th>
                    <th class="torrent-listings-format"></th>
                    <th class="torrents-filename torrent-listings-overview">
                        <div sortable wire:click="sortBy('name')"
                             :direction="$sortField === 'name' ? $sortDirection : null"
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
                        <div sortable wire:click="sortBy('size')"
                             :direction="$sortField === 'size' ? $sortDirection : null"
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
                        @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id'], 'genres' => ['name']])->find($torrent->igdb) @endphp
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

                                @if ($torrent->category->game_meta && isset($meta) && $meta->cover['image_id'] && $meta->name)
                                    <img src="https://images.igdb.com/igdb/image/upload/t_cover_big/{{ $meta->cover['image_id'] }}.jpg"
                                         class="show-poster"
                                         data-name='<i style="color: #a5a5a5;">{{ $meta->name ?? 'N/A' }}</i>'
                                         data-image='<img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover['image_id'] }}.jpg"
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
                                             class="torrent-poster-img-small show-poster"
                                             alt="{{ __('torrent.poster') }}">
                                    @endif
                                @endif
                            </div>
                            <div class="body_description">
                                <h3 class="description_title">
                                    <a href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                        {{ $torrent->name }}
                                    </a>
                                </h3>
                                @if (isset($meta->genres) && ($torrent->category->movie_meta || $torrent->category->tv_meta))
                                    @foreach ($meta->genres as $genre)
                                        <span class="genre-label">
                                        <a href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}">
                                            <i class="{{ config('other.font-awesome') }} fa-theater-masks"></i> {{ $genre->name }}
                                        </a>
                                    </span>
                                    @endforeach
                                @endif
                                @if (isset($meta->genres) && $torrent->category->game_meta)
                                    @foreach ($meta->genres as $genre)
                                        <span class="genre-label">
                                        <i class="{{ config('other.font-awesome') }} fa-theater-masks"></i> {{ $genre['name'] }}
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
            {{ $torrents->links('partials.pagination') }}
        </div>
    </section>
</div>

