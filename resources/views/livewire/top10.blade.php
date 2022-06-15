<div>
    <table class="table table-condensed table-bordered" id="top10-table">
        <thead>
        <tr>
            <th class="text-center dark-th" colspan="12">
                <span style="font-size: 16px;">Top 10 (Day)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($torrentsDay as $torrent)
            @php $meta = null @endphp
            @if ($torrent->category->tv_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Tv::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->movie_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Movie::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->game_meta)
                @if ($torrent->igdb || $torrent->igdb != 0)
                    @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                @endif
            @endif


                <tr>
                    <td class="torrent-listings-poster" style="width: 1%;">
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
                    </td>

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
                        @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
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

                        @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
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

    <table class="table table-condensed table-bordered" id="top10-table">
        <thead>
        <tr>
            <th class="text-center dark-th" colspan="12">
                <span style="font-size: 16px;">Top 10 (Week)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        @if($torrentsWeek)
        @foreach($torrentsWeek as $torrent)
            @php $meta = null @endphp
            @if ($torrent->category->tv_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Tv::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->movie_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Movie::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->game_meta)
                @if ($torrent->igdb || $torrent->igdb != 0)
                    @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                @endif
            @endif


            <tr>
                <td class="torrent-listings-poster" style="width: 1%;">
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
                </td>

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
                    @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
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

                    @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
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
        @endif
        </tbody>
    </table>

    <table class="table table-condensed table-bordered" id="top10-table">
        <thead>
        <tr>
            <th class="text-center dark-th" colspan="12">
                <span style="font-size: 16px;">Top 10 (Month)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        @if($torrentsMonth)
        @foreach($torrentsMonth as $torrent)
            @php $meta = null @endphp
            @if ($torrent->category->tv_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Tv::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->movie_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Movie::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->game_meta)
                @if ($torrent->igdb || $torrent->igdb != 0)
                    @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                @endif
            @endif


            <tr>
                <td class="torrent-listings-poster" style="width: 1%;">
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
                </td>

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
                    @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
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

                    @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
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
            @endif
        </tbody>
    </table>

    <table class="table table-condensed table-bordered" id="top10-table">
        <thead>
        <tr>
            <th class="text-center dark-th" colspan="12">
                <span style="font-size: 16px;">Top 10 (Year)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        @if($torrentsYear)
        @foreach($torrentsYear as $torrent)
            @php $meta = null @endphp
            @if ($torrent->category->tv_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Tv::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->movie_meta)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $meta = App\Models\Movie::where('id', '=', $torrent->tmdb)->first() @endphp
                @endif
            @endif
            @if ($torrent->category->game_meta)
                @if ($torrent->igdb || $torrent->igdb != 0)
                    @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                @endif
            @endif


            <tr>
                <td class="torrent-listings-poster" style="width: 1%;">
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
                </td>

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
                    @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
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

                    @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
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
            @endif
        </tbody>
    </table>

    <table class="table table-condensed table-bordered" id="top10-table">
        <thead>
        <tr>
            <th class="text-center dark-th" colspan="12">
                <span style="font-size: 16px;">Top 10 (All Time)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        @if($torrentsAll)
            @foreach($torrentsAll as $torrent)
                @php $meta = null @endphp
                @if ($torrent->category->tv_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
                        @php $meta = App\Models\Tv::where('id', '=', $torrent->tmdb)->first() @endphp
                    @endif
                @endif
                @if ($torrent->category->movie_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
                        @php $meta = App\Models\Movie::where('id', '=', $torrent->tmdb)->first() @endphp
                    @endif
                @endif
                @if ($torrent->category->game_meta)
                    @if ($torrent->igdb || $torrent->igdb != 0)
                        @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url', 'image_id']])->find($torrent->igdb) @endphp
                    @endif
                @endif


                <tr>
                    <td class="torrent-listings-poster" style="width: 1%;">
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
                    </td>

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
                        @if(auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
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

                        @if ($torrent->bumped_at != $torrent->created_at && $torrent->bumped_at < Illuminate\Support\Carbon::now()->addDay(2))
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
        @endif
        </tbody>
    </table>
</div>

