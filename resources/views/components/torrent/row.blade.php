
<tr
    @class([
        'torrent-search--list__row',
        'torrent-search--list__success' => $torrent->sticky
    ])
    data-torrent-id="{{ $torrent->id }}"
    data-igdb-id="{{ $torrent->igdb }}"
    data-imdb-id="{{ $torrent->imdb }}"
    data-tmdb-id="{{ $torrent->tmdb }}"
    data-tvdb-id="{{ $torrent->tvdb }}"
    data-mal-id="{{ $torrent->mal }}"
    data-category-id="{{ $torrent->category_id }}"
    data-type-id="{{ $torrent->type_id }}"
    data-type-id="{{ $torrent->resolution_id }}"
>
    @if (auth()->user()->show_poster == 1)
        <td class="torrent-search--list__poster">
            <a href="{{ route('torrents.similar', ['category_id' => $torrent->category_id, 'tmdb' => $torrent->tmdb]) }}">
                @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                    <img
                        src="{{ isset($meta->poster) ? tmdb_image('poster_small', $meta->poster) : 'https://via.placeholder.com/90x135' }}"
                        class="torrent-search--list__poster-img"
                        loading="lazy"
                        alt="{{ __('torrent.poster') }}"
                    >
                @endif
                @if ($torrent->category->game_meta)
                    <img
                        style="height: 80px;"
                        src="{{ isset($meta->cover) ? 'https://images.igdb.com/igdb/image/upload/t_cover_small_2x/'.$meta->cover['image_id'].'.png' : 'https://via.placeholder.com/90x135' }}"
                        class="torrent-search--list__poster-img"
                        loading="lazy"
                        alt="{{ __('torrent.poster') }}"
                    >
                @endif
                @if ($torrent->category->music_meta)
                    <img
                        src="https://via.placeholder.com/90x135"
                        class="torrent-search--list__poster-img"
                        loading="lazy"
                        alt="{{ __('torrent.poster') }}"
                    >
                @endif
                @if ($torrent->category->no_meta)
                    @if(file_exists(public_path().'/files/img/torrent-cover_'.$torrent->id.'.jpg'))
                        <img
                            src="{{ url('files/img/torrent-cover_'.$torrent->id.'.jpg') }}"
                            class="torrent-search--list__poster-img"
                            loading="lazy"
                            alt="{{ __('torrent.poster') }}"
                        >
                    @else
                        <img
                            src="https://via.placeholder.com/400x600"
                            class="torrent-search--list__poster-img"
                            loading="lazy"
                            alt="{{ __('torrent.poster') }}"
                        >
                    @endif
                @endif
            </a>
        </td>
    @endif
    <td class="torrent-search--list__format">
        <div>
            <div class="torrent-search--list__category">
                @if ($torrent->category->image !== null)
                    <img
                        src="{{ url('files/img/' . $torrent->category->image) }}"
                        title="{{ $torrent->category->name }} {{ strtolower(__('torrent.torrent')) }}"
                        alt="{{ $torrent->category->name }}"
                        loading="lazy"
                        @style([
                            'height: 32px',
                            'padding-top: 1px' => $torrent->category->movie_meta || $torrent->category->tv_meta,
                            'padding-top: 12px' => ! ($torrent->category->movie_meta || $torrent->category->tv_meta),
                        ])
                    >
                @else
                    <i
                        class="{{ $torrent->category->icon }} torrent-icon"
                        @style([
                            'font-size: 24px',
                            'padding-top: 1px' => $torrent->category->movie_meta || $torrent->category->tv_meta,
                            'padding-top: 12px' => ! ($torrent->category->movie_meta || $torrent->category->tv_meta),
                        ])
                    ></i>
                @endif
            </div>
            <div class="torrent-search--list__resolution-and-type">
                @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                    <span class="torrent-search--list__resolution">
                        {{ $torrent->resolution->name ?? 'No Res' }}
                    </span>
                @endif
                <span class="torrent-search--list__type">
                    {{ $torrent->type->name }}
                </span>
            </div>
        </div>
    </td>
    <td class="torrent-search--list__overview">
        <div>
            <a class="torrent-search--list__name" href="{{ route('torrent', ['id' => $torrent->id]) }}">
                {{ $torrent->name }}
            </a>
            <x-user_tag class="torrent-search--list__uploader" :user="$torrent->user" :anon="$torrent->anon" />
            <div class="torrent-search--list__icons">
                <i class="{{ config('other.font-awesome') }} fa-heartbeat torrent-search--list__thanks">{{ $torrent->thanks_count }}</i>
                <i class="{{ config('other.font-awesome') }} fa-comment-alt-lines torrent-search--list__comments">{{ $torrent->comments_count }}</i>
                @if ($torrent->internal)
                    <i
                        class="{{ config('other.font-awesome') }} fa-magic torrent-search--list__internal"
                        title="{{ __('torrent.internal-release') }}"
                    ></i>
                @endif
                @if ($torrent->personal_release)
                    <i
                        class="{{ config('other.font-awesome') }} fa-user-plus torrent-search--list__personal"
                        title="Personal Release"
                    ></i>
                @endif
                @if ($torrent->stream)
                    <i
                        class="{{ config('other.font-awesome') }} fa-play text-red torrent-search--list__stream-optimized"
                        title="{{ __('torrent.stream-optimized') }}"
                    ></i>
                @endif
                @if ($torrent->featured)
                    <i
                        class="{{ config('other.font-awesome') }} fa-id-certificate text-pink torrent-search--list__featured"
                        title="{{ __('torrent.featured') }}"
                    ></i>
                @endif
                @php
                    $alwaysFreeleech = $personalFreeleech || $torrent->freeleechTokens_exists || auth()->user()->group->is_freeleech || config('other.freeleech')
                @endphp
                @if ($alwaysFreeleech || $torrent->free)
                    <i
                        @class([
                            'torrent-search--list__freeleech '.config('other.font-awesome'),
                            'fa-star' => $alwaysFreeleech || (90 <= $torrent->free && $torrent->fl_until === null),
                            'fa-star-half' => ! $alwaysFreeleech && $torrent->free < 90 && $torrent->fl_until === null,
                            'fa-calendar-star' => ! $alwaysFreeleech && $torrent->fl_until !== null,
                        ])
                        title="@if($personalFreeleech){{ __('torrent.personal-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->freeleechTokens_exists){{ __('torrent.freeleech-token') }}&NewLine;@endif&ZeroWidthSpace;@if(auth()->user()->group->is_freeleech){{ __('torrent.special-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if(config('other.freeleech')){{ __('torrent.global-freeleech') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->free > 0){{ $torrent->free }}% {{ __('common.free') }}@if($torrent->fl_until !== null) (expires {{ $torrent->fl_until->diffForHumans() }})@endif&ZeroWidthSpace;@endif"
                    ></i>
                @endif
                @if (config('other.doubleup') || auth()->user()->group->is_double_upload || $torrent->doubleup)
                    <i
                        class="{{ config('other.font-awesome') }} fa-chevron-double-up text-green torrent-search--list__double-upload"
                        title="@if(config('other.doubleup')){{ __('torrent.global-double-upload') }}&NewLine;@endif&ZeroWidthSpace;@if(auth()->user()->group->is_double_upload){{ __('torrent.special-double_upload') }}&NewLine;@endif&ZeroWidthSpace;@if($torrent->doubleup > 0){{ $torrent->doubleup }}% {{ __('torrent.double-upload') }}@if($torrent->du_until !== null) (expires {{ $torrent->du_until->diffForHumans() }})@endif&ZeroWidthSpace;@endif"
                    ></i>
                @endif
                @if ($torrent->sticky)
                    <i
                        class="{{ config('other.font-awesome') }} fa-thumbtack text-black torrent-search--list__sticky"
                        title="{{ __('torrent.sticky') }}"
                    ></i>
                @endif
                @if ($torrent->highspeed)
                    <i
                        class="{{ config('other.font-awesome') }} fa-tachometer text-red torrent-search--list__highspeed"
                        title="{{ __('common.high-speeds') }}"
                    ></i>
                @endif
                @if ($torrent->sd)
                    <i
                        class="{{ config('other.font-awesome') }} fa-ticket text-orange torrent-search--list__sd"
                        title="{{ __('torrent.sd-content') }}"
                    ></i>
                @endif
                @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
                    <i
                        class="{{ config('other.font-awesome') }} fa-level-up-alt text-gold torrent-search--list__bumped"
                        title="{{ __('torrent.recent-bumped') }}: {{ $torrent->bumped_at }}"
                    ></i>
                @endif
            </div>
        </div>
    </td>
    <td class="torrent-search--list__buttons">
        <div>
            @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
                <a
                    class="torrent-search--list__edit form__standard-icon-button"
                    href="{{ route('edit_form', ['id' => $torrent->id]) }}"
                    title="{{ __('common.edit') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-pencil-alt"></i>
                </a>
            @endif
            @livewire('small-bookmark-button', ['torrent' => $torrent, 'isBookmarked' => $torrent->bookmarks_exists, 'user' => auth()->user()], key('torrent-'.$torrent->id))
            @if (config('torrent.download_check_page'))
                <a
                    class="torrent-search--list__file form__standard-icon-button"
                    href="{{ route('download_check', ['id' => $torrent->id]) }}"
                    download
                    title="{{ __('common.download') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                </a>
            @else
                <a
                    class="torrent-search--list__file form__standard-icon-button"
                    href="{{ route('download', ['id' => $torrent->id]) }}"
                    download
                    title="{{ __('common.download') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                </a>
            @endif
            @if (config('torrent.magnet'))
                <a
                    class="torrent-search--list__maget form__contained-icon-button form__contained-icon-button--filled"
                    href="magnet:?dn={{ $torrent->name }}&xt=urn:btih:{{ $torrent->info_hash }}&as={{ route('torrent.download.rsskey', ['id' => $torrent->id, 'rsskey' => auth()->user()->rsskey ]) }}&tr={{ route('announce', ['passkey' => auth()->user()->passkey]) }}&xl={{ $torrent->size }}"
                    download
                    title="{{ __('common.magnet') }}"
                >
                    <i class="{{ config('other.font-awesome') }} fa-magnet"></i>
                </a>
            @endif
        </div>
    </td>
    @if ($torrent->category->game_meta)
        <td class="torrent-search--list__rating {{ rating_color($meta->rating ?? 'text-white') }}">
            <span>{{ round($meta->rating ?? 0) }}%</span>
        </td>
    @elseif ($torrent->category->movie_meta || $torrent->category->tv_meta)
        <td
            class="torrent-search--list__rating"
            title="{{ $meta->vote_count ?? 0 }} Votes"
        >
            <span class="{{ rating_color($meta->vote_average ?? 'text-white') }}">{{ round(($meta->vote_average ?? 0) * 10) }}%</span>
        </td>
    @else
        <td class="torrent-search--list__rating">N/A</td>
    @endif
    <td class="torrent-search--list__size">
        <span>{{ $torrent->getSize() }}</span>
    </td>
    <td class="torrent-search--list__seeders">
        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->seeders }}</span>
        </a>
    </td>
    <td class="torrent-search--list__leechers">
        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->leechers }}</span>
        </a>
    </td>
    <td class="torrent-search--list__completed">
        <a href="{{ route('history', ['id' => $torrent->id]) }}">
            <span>{{ $torrent->times_completed }}</span>
        </a>
    </td>
    <td class="torrent-search--list__age">
        <span>{{ $torrent->created_at->diffForHumans() }}</span>
    </td>
</tr>
