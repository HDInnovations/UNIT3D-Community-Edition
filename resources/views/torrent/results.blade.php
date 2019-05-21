@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
<div class="table-responsive">
    <div class="text-center">
        @if($links)
            {{ $links->links() }}
        @else
            @if($torrents->links())
                {{ $torrents->links() }}
            @endif
        @endif
    </div>
    <table class="table table-condensed table-bordered table-striped table-hover">
        <thead>
        <tr>
            @if ($user->show_poster == 1)
                <th>@lang('torrent.poster')</th>
            @else
                <th></th>
            @endif
            <th>@lang('torrent.category')/@lang('torrent.type')</th>
            <th style="white-space: nowrap !important;">@sortablelink('name', trans('common.name'), '', ['id'=>'name','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "name" ? $direction : 0)])</th>
            <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-clock"></i> @sortablelink('created_at', (trans('torrent.created_at')), '', ['id'=>'created_at','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "created_at" ? $direction : 0)])</th>
            <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-file"></i> @sortablelink('size', (trans('torrent.size')), '', ['id'=>'size','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "size" ? $direction : 0)])</th>
            <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i> @sortablelink('seeders', 'S', '', ['id'=>'seeders','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "seeders" ? $direction : 0)])</th>
            <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i> @sortablelink('leechers', 'L', '', ['id'=>'leechers','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "leechers" ? $direction : 0)])</th>
            <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-check-square"></i> @sortablelink('times_completed', 'C', '', ['id'=>'times_completed','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "times_completed" ? $direction : 0)])</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($torrents as $torrent)
            @if ($torrent->category_id == 2)
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $movie = $client->scrape('tv', null, $torrent->tmdb); @endphp
                @else
                    @php $movie = $client->scrape('tv', 'tt'. $torrent->imdb); @endphp
                @endif
            @else
                @if ($torrent->tmdb || $torrent->tmdb != 0)
                    @php $movie = $client->scrape('movie', null, $torrent->tmdb); @endphp
                @else
                    @php $movie = $client->scrape('movie', 'tt'. $torrent->imdb); @endphp
                @endif
            @endif

            @if ($torrent->sticky == 1)
                <tr class="success">
            @else
                <tr>
            @endif
                    <td>
                        @if ($user->show_poster == 1)
                            <div class="torrent-poster pull-left">
                                <img src="{{ $movie->poster ?? 'https://via.placeholder.com/600x900'}}"
                                     data-name='<i style="color: #a5a5a5;">{{ $movie->title }}</i>' data-image='<img src="{{ $movie->poster }}" alt="@lang('torrent.poster')" style="height: 1000px;">'
                                     class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
                            </div>
                        @else
                            <div class="torrent-poster pull-left"></div>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('category', ['slug' => $torrent->category->slug, 'id' => $torrent->category->id]) }}">
                            <div class="text-center">
                                <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                   data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                   style="padding-bottom: 6px;"></i>
                            </div>
                        </a>
                        <div class="text-center">
                            <span class="label label-success" data-toggle="tooltip" data-original-title="@lang('torrent.type')">
                                {{ $torrent->type }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            {{ $torrent->name }}
                        </a>

                        {{--@if ($torrent->category_id == 5)
                            <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="THIS IS A TRAILER ONLY!">
                                <i class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i>
                            </button>
                        @endif--}}

                        @if (config('torrent.download_check_page') == 1)
                            <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('common.download')">
                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                </button>
                            </a>
                        @else
                            <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('common.download')">
                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                </button>
                            </a>
                        @endif

                        <span data-toggle="tooltip" data-original-title="Bookmark" id="torrentBookmark{{ $torrent->id }}" torrent="{{ $torrent->id }}" state="{{ $torrent->bookmarked() ? 1 : 0}}" class="torrentBookmark"></span>

                        @php $history = \App\Models\History::where('user_id', '=', $user->id)->where('info_hash', '=', $torrent->info_hash)->first(); @endphp
                        @if ($history)
                            @if ($history->seeder == 1 && $history->active == 1)
                                <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('torrent.currently-seeding')!">
                                    <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 1)
                                <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('torrent.currently-leeching')!">
                                    <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('torrent.not-completed')!">
                                    <i class="{{ config('other.font-awesome') }} fa-hand-paper"></i>
                                </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                        data-original-title="@lang('torrent.completed-not-seeding')!">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                </button>
                            @endif
                        @endif

                        <br>
                        @if ($torrent->anon == 1)
                            <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i> @lang('common.anonymous')
                                @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        ({{ $torrent->user->username }})
                                    </a>
                                @endif
                            </span>
                        @else
                            <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i>
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        {{ $torrent->user->username }}
                                    </a>
                            </span>
                        @endif

                        @if ($torrent->category->meta == 1)
                            @if ($user->ratings == 1)
                            <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" target="_blank">
                                <span class="badge-extra text-bold">
                                    <span class="text-gold movie-rating-stars">
                                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                           data-original-title="@lang('torrent.view-more')"></i>
                                    </span>
                                    {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} @lang('torrent.votes'))
                                </span>
                            </a>
                            @else
                            @if ($torrent->category_id == 2)
                                <a href="https://www.themoviedb.org/tv/{{ $movie->tmdb }}?language={{ config('app.locale') }}" target="_blank">
                            @else
                                <a href="https://www.themoviedb.org/movie/{{ $movie->tmdb }}?language={{ config('app.locale') }}" target="_blank">
                            @endif
                            <span class="badge-extra text-bold">
                                <span class="text-gold movie-rating-stars">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                        data-original-title="@lang('torrent.view-more')"></i>
                                </span>
                                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} @lang('torrent.votes'))
                            </span>
                            </a>
                            @endif
                        @endif

                        <span class="badge-extra text-bold text-pink">
                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="@lang('torrent.thanks-given')"></i>
                            {{ $torrent->thanks_count }}
                        </span>

                        <a href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id, 'hash' => '#comments']) }}">
                            <span class="badge-extra text-bold text-green">
                                <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="@lang('common.comments')"></i>
                                {{ $torrent->comments_count }}
                            </span>
                        </a>

                        @if ($torrent->internal == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.internal-release')' style="color: rgb(186,175,146);"></i> @lang('torrent.internal')
                            </span>
                        @endif

                        @if ($torrent->stream == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.stream-optimized')'></i> @lang('torrent.stream-optimized')
                            </span>
                        @endif

                        @if ($torrent->featured == 0)
                        @if ($torrent->doubleup == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.double-upload')'></i> @lang('torrent.double-upload')
                            </span>
                        @endif
                        @if ($torrent->free == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-star text-gold' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.freeleech')'></i> @lang('torrent.freeleech')
                            </span>
                        @endif
                        @endif

                        @if ($personal_freeleech)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-id-badge text-orange' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.personal-freeleech')'></i> @lang('torrent.personal-freeleech')
                            </span>
                        @endif

                        @php $freeleech_token = \App\Models\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first(); @endphp
                        @if ($freeleech_token)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-coins text-bold' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.freeleech-token')'></i> @lang('torrent.freeleech-token')
                            </span>
                        @endif

                        @if ($torrent->featured == 1)
                            <span class='badge-extra text-bold' style='background-image:url(https://i.imgur.com/F0UCb7A.gif);'>
                                <i class='{{ config("other.font-awesome") }} fa-certificate text-pink' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.featured')'></i> @lang('torrent.featured')
                            </span>
                        @endif

                        @if ($user->group->is_freeleech == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-trophy text-purple' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.special-freeleech')'></i> @lang('torrent.special-freeleech')
                            </span>
                        @endif

                        @if (config('other.freeleech') == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-blue' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.global-freeleech')'></i> @lang('torrent.global-freeleech')
                            </span>
                        @endif

                        @if (config('other.doubleup') == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-globe text-green' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.global-double-upload')'></i> @lang('torrent.global-double-upload')
                            </span>
                        @endif

                        @if ($torrent->leechers >= 5)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.hot')!'></i> @lang('common.hot')
                            </span>
                        @endif

                        @if ($torrent->sticky == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.sticky')!'></i> @lang('torrent.sticky')
                            </span>
                        @endif

                        @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.new')!'></i> @lang('common.new')
                            </span>
                        @endif

                        @if ($torrent->highspeed == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' title=''
                                    data-original-title='@lang('common.high-speeds')!'></i> @lang('common.high-speeds')
                            </span>
                        @endif

                        @if ($torrent->sd == 1)
                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' title=''
                                    data-original-title='@lang('torrent.sd-content')!'></i> @lang('torrent.sd-content')
                            </span>
                        @endif

                        @php $torrent_tags = App\Models\TagTorrent::where('torrent_id', '=', $torrent->id)->get(); @endphp
                        @if ($torrent_tags)
                        @foreach($torrent_tags as $torrent_tag)
                            <span class="badge-extra text-bold">
                                <i class='{{ config("other.font-awesome") }} fa-tag' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.genre')'></i> {{ $torrent_tag->genre->name }}
                            </span>
                        @endforeach
                        @endif
                    </td>

                    <td>
                        <time>{{ $torrent->created_at->diffForHumans() }}</time>
                    </td>
                    <td>
                        <span class='badge-extra text-blue text-bold'>{{ $torrent->getSize() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-green text-bold'>
                                {{ $torrent->seeders }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('peers', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-red text-bold'>
                                {{ $torrent->leechers }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $torrent->times_completed }} @lang('common.times')
                            </span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="text-center">
        @if($links)
            {{ $links->links() }}
        @else
            @if($torrents->links())
                {{ $torrents->links() }}
            @endif
        @endif
    </div>
</div>
