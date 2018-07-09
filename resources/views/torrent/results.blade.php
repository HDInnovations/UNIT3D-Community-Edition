<div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped table-hover">
        <thead>
        <tr>
            {{--@if($user->show_poster == 1)
                <th>Poster</th>
            @else
                <th></th>
            @endif--}}
            <th>Category/Type</th>
            <th>{{ trans('common.name') }}</th>
            <th><i class="fa fa-clock-o"></i></th>
            <th><i class="fa fa-file"></i></th>
            <th><i class="fa fa-check-square-o"></i></th>
            <th><i class="fa fa-arrow-circle-up"></i></th>
            <th><i class="fa fa-arrow-circle-down"></i></th>
        </tr>
        </thead>

        <tbody>
        @foreach($torrents as $torrent)

            {{--@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') , config('api-keys.omdb')) @endphp
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
            @endif--}}

            @if ($torrent->sticky == 1)
                <tr class="success">
            @else
                <tr>
            @endif
                    {{--<td>
                        @if ($user->show_poster == 1)
                            <div class="torrent-poster pull-left">
                                <img src="{{ $movie->poster ?? 'https://via.placeholder.com/600x900'}}"
                                     data-poster-mid="{{ $movie->poster ?? 'https://via.placeholder.com/600x900'}}"
                                     class="img-tor-poster torrent-poster-img-small" alt="Poster">
                            </div>
                        @else
                            <div class="torrent-poster pull-left"></div>
                        @endif
                    </td>--}}

                    <td>
                        <a href="{{ route('category', ['slug' => $torrent->category->slug, 'id' => $torrent->category->id]) }}">
                            <div class="text-center">
                                <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip" title=""
                                   data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                   style="padding-bottom: 6px;"></i>
                            </div>
                        </a>
                        <div class="text-center">
                            <span class="label label-success" data-toggle="tooltip" title="" data-original-title="Type">
                                {{ $torrent->type }}
                            </span>
                        </div>
                    </td>

                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            {{ $torrent->name }}
                        </a>
                        @if (config('torrent.download_check_page') == 1)
                            <a href="{{ route('download_check', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title=""
                                        data-original-title="Download Torrent">
                                    <i class="fa fa-download"></i>
                                </button>
                            </a>
                        @else
                            <a href="{{ route('download', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip" title=""
                                        data-original-title="Download Torrent">
                                    <i class="fa fa-download"></i>
                                </button>
                            </a>
                        @endif

                        {{--<smallbookmark :id="{{ $torrent->id }}" :state="{{ $torrent->bookmarked()  ? 1 : 0}}"></smallbookmark>--}}

                        @php $history = \App\History::where('user_id', '=', $user->id)->where('info_hash', '=', $torrent->info_hash)->first(); @endphp
                        @if ($history)
                            @if ($history->seeder == 1 && $history->active == 1)
                                <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip" title=""
                                        data-original-title="Currently Seeding!">
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 1)
                            <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip" title=""
                                    data-original-title="Currently Leeching!">
                                <i class="fa fa-arrow-down"></i>
                            </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                            <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip" title=""
                                    data-original-title="Started Downloading But Never Completed!">
                                <i class="fa fa-hand-paper-o"></i>
                            </button>
                            @endif

                            @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                            <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip" title=""
                                    data-original-title="You Completed This Download But Are No Longer Seeding It!">
                                <i class="fa fa-thumbs-down"></i>
                            </button>
                            @endif
                        @endif

                        <br>
                        @if ($torrent->anon == 1)
                            <span class="badge-extra text-bold">
                                <i class="fa fa-upload" data-toggle="tooltip" title="" data-original-title="Uploaded By"></i> By ANONYMOUS USER
                                @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        ({{ $torrent->user->username }})
                                    </a>
                                @endif
                            </span>
                        @else
                            <span class="badge-extra text-bold">
                                <i class="fa fa-upload" data-toggle="tooltip" title="" data-original-title="Uploaded By"></i> By
                                    <a href="{{ route('profile', ['username' => $torrent->user->username, 'id' => $torrent->user->id]) }}">
                                        {{ $torrent->user->username }}
                                    </a>
                            </span>
                        @endif

                        {{--@if ($torrent->category->meta == 1)
                            @if ($user->ratings == 1) {
                            <a rel="nofollow" href="https://anon.to?http://www.imdb.com/title/tt{{ $torrent->imdb }}">
                                <span class="badge-extra text-bold">
                                    <span class="text-gold movie-rating-stars">
                                        <i class="fa fa-star" data-toggle="tooltip" title=""
                                           data-original-title="View More"></i>
                                    </span>
                                    {{ $movie->imdbRating }}/10 ({{ $movie->imdbVotes }} votes)
                                </span>
                            </a>
                            @else
                            @if ($torrent->category_id == 2)
                                <a rel="nofollow" href="https://www.themoviedb.org/tv/{{ $movie->tmdb }}">
                            @else
                                <a rel="nofollow" href="https://www.themoviedb.org/movie/{{ $movie->tmdb }}">
                            @endif
                            <span class="badge-extra text-bold">
                                <span class="text-gold movie-rating-stars">
                                    <i class="fa fa-star" data-toggle="tooltip" title=""
                                        data-original-title="View More"></i>
                                </span>
                                {{ $movie->tmdbRating }}/10 ({{ $movie->tmdbVotes }} votes)
                            </span>
                            </a>
                            @endif
                        @endif--}}

                        <span class="badge-extra text-bold text-pink">
                            <i class="fa fa-heart" data-toggle="tooltip" title="" data-original-title="Thanks Given"></i>
                                {{ $torrent->thanks()->count() }}
                        </span>

                        @if ($torrent->stream == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-play text-red' data-toggle='tooltip' title=''
                                    data-original-title='Stream Optimized'></i> Stream Optimized
                            </span>
                        @endif

                        @if ($torrent->featured == 0)
                        @if ($torrent->doubleup == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-diamond text-green' data-toggle='tooltip' title=''
                                    data-original-title='Double upload'></i> Double Upload
                            </span>
                        @endif
                        @if ($torrent->free == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-star text-gold' data-toggle='tooltip' title=''
                                    data-original-title='100% Free'></i> 100% Free
                            </span>
                        @endif
                        @endif

                        @if ($personal_freeleech)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-id-badge text-orange' data-toggle='tooltip' title=''
                                    data-original-title='Personal FL'></i> Personal FL
                            </span>
                        @endif

                        @php $freeleech_token = \App\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first(); @endphp
                        @if ($freeleech_token)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-viacoin text-bold' data-toggle='tooltip' title=''
                                    data-original-title='Freeleech Token'></i> Freeleech Token
                            </span>
                        @endif

                        @if ($torrent->featured == 1)
                            <span class='badge-extra text-bold' style='background-image:url(https://i.imgur.com/F0UCb7A.gif);'>
                                <i class='fa fa-certificate text-pink' data-toggle='tooltip' title=''
                                    data-original-title='Featured Torrent'></i> Featured
                            </span>
                        @endif

                        @if ($user->group->is_freeleech == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-trophy text-purple' data-toggle='tooltip' title=''
                                    data-original-title='Special FL'></i> Special FL
                            </span>
                        @endif

                        @if (config('other.freeleech') == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-globe text-blue' data-toggle='tooltip' title=''
                                    data-original-title='Global FreeLeech'></i> Global FreeLeech
                            </span>
                        @endif

                        @if (config('other.doubleup') == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-globe text-green' data-toggle='tooltip' title=''
                                    data-original-title='Double Upload'></i> Global Double Upload
                            </span>
                        @endif

                        @if ($torrent->leechers >= 5)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='Hot!'></i> Hot
                            </span>
                        @endif

                        @if ($torrent->sticky == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-thumb-tack text-black' data-toggle='tooltip' title=''
                                    data-original-title='Sticky!'></i> Sticky
                            </span>
                        @endif

                        @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='NEW!'></i> NEW
                            </span>
                        @endif

                        @if ($torrent->highspeed == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-tachometer text-red' data-toggle='tooltip' title=''
                                    data-original-title='High Speeds!'></i> High Speeds
                            </span>
                        @endif

                        @if ($torrent->sd == 1)
                            <span class='badge-extra text-bold'>
                                <i class='fa fa-ticket text-orange' data-toggle='tooltip' title=''
                                    data-original-title='SD Content!'></i> SD Content
                            </span>
                        @endif
                    </td>

                    <td>
                        <time>{{ $torrent->created_at->diffForHumans() }}</time>
                    </td>
                    <td>
                        <span class='badge-extra text-blue text-bold'>{{ $torrent->getSize() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('history', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $torrent->times_completed }} {{ trans('common.times') }}
                            </span>
                        </a>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="text-center">
        {{ $torrents->links() }}
    </div>
</div>

