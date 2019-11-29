@php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb') , config('api-keys.tvdb') ,
config('api-keys.omdb')) @endphp
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
                <th style="white-space: nowrap !important;">@sortablelink('name', trans('common.name'), '',
                    ['id'=>'name','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting
                    == "name" ? $direction : 0)])</th>
                <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-clock"></i>
                    @sortablelink('created_at', (trans('torrent.created_at')), '',
                    ['id'=>'created_at','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting &&
                    $sorting == "created_at" ? $direction : 0)])</th>
                <th style="white-space: nowrap !important;"><i class="{{ config('other.font-awesome') }} fa-file"></i>
                    @sortablelink('size', (trans('torrent.size')), '', ['id'=>'size','class'=>'facetedSearch
                    facetedSort','trigger'=>'sort','state'=> ($sorting && $sorting == "size" ? $direction : 0)])</th>
                <th style="white-space: nowrap !important;"><i
                        class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i> @sortablelink('seeders', 'S',
                    '', ['id'=>'seeders','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=> ($sorting &&
                    $sorting == "seeders" ? $direction : 0)])</th>
                <th style="white-space: nowrap !important;"><i
                        class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i> @sortablelink('leechers',
                    'L', '', ['id'=>'leechers','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=>
                    ($sorting && $sorting == "leechers" ? $direction : 0)])</th>
                <th style="white-space: nowrap !important;"><i
                        class="{{ config('other.font-awesome') }} fa-check-square"></i> @sortablelink('times_completed',
                    'C', '', ['id'=>'times_completed','class'=>'facetedSearch facetedSort','trigger'=>'sort','state'=>
                    ($sorting && $sorting == "times_completed" ? $direction : 0)])</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($torrents as $torrent)
            @php $meta = null; @endphp
                @if ($torrent->category->tv_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
            @php $meta = $client->scrape('tv', null, $torrent->tmdb); @endphp
                    @else
            @php $meta = $client->scrape('tv', 'tt'. $torrent->imdb); @endphp
                    @endif
                @endif
                @if ($torrent->category->movie_meta)
                    @if ($torrent->tmdb || $torrent->tmdb != 0)
            @php $meta = $client->scrape('movie', null, $torrent->tmdb); @endphp
                    @else
            @php $meta = $client->scrape('movie', 'tt'. $torrent->imdb); @endphp
                    @endif
                @endif
                @if ($torrent->category->game_meta)
                    @if ($torrent->igdb || $torrent->igdb != 0)
                        @php $meta = MarcReichel\IGDBLaravel\Models\Game::with(['cover' => ['url',
                        'image_id']])->find($torrent->igdb); @endphp
                    @endif
                @endif
    
                @if ($torrent->sticky == 1)
                    <tr class="success">
                    @else
                    <tr>
                    @endif
                    <td style="width: 1%;">
                        @if ($user->show_poster == 1)
                            <div class="torrent-poster pull-left">
                                @if ($torrent->category->movie_meta || $torrent->category->tv_meta && isset($meta) &&
                                    $meta->poster && $meta->title)
                                    <img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}"
                                        data-name='<i style="color: #a5a5a5;">{{ $meta->title ?? 'N/A' }}</i>'
                                        data-image='<img src="{{ $meta->poster ?? 'https://via.placeholder.com/600x900' }}" alt="@lang('
                                        torrent.poster')" style="height: 1000px;">'
                                    class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
                                @endif
        
                                @if ($torrent->category->game_meta && isset($meta) && $meta->cover->image_id && $meta->name)
                                    <img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover->image_id }}.jpg"
                                        data-name='<i style="color: #a5a5a5;">{{ $meta->name ?? 'N/A' }}</i>'
                                        data-image='<img src="https://images.igdb.com/igdb/image/upload/t_original/{{ $meta->cover->image_id }}.jpg" alt="@lang('
                                        torrent.poster')" style="height: 1000px;">'
                                    class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
                                @endif
        
                                @if ($torrent->category->no_meta || $torrent->category->music_meta || ! $meta)
                                    <img src="https://via.placeholder.com/600x900" data-name='<i style="color: #a5a5a5;">N/A</i>'
                                        data-image='<img src="https://via.placeholder.com/600x900" alt="@lang(' torrent.poster')"
                                        style="height: 1000px;">'
                                    class="torrent-poster-img-small show-poster" alt="@lang('torrent.poster')">
                                @endif
                            </div>
                        @else
                            <div class="torrent-poster pull-left"></div>
                        @endif
                    </td>
    
                    <td style="width: 1%;">
                        @if ($torrent->category->image != null)
                            <a href="{{ route('categories.show', ['id' => $torrent->category->id]) }}">
                                <div class="text-center">
                                    <img src="{{ url('files/img/' . $torrent->category->image) }}" data-toggle="tooltip"
                                        data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                        style="padding-bottom: 10px;" alt="{{ $torrent->category->name }}">
                                </div>
                            </a>
                        @else
                            <a href="{{ route('categories.show', ['id' => $torrent->category->id]) }}">
                                <div class="text-center">
                                    <i class="{{ $torrent->category->icon }} torrent-icon" data-toggle="tooltip"
                                        data-original-title="{{ $torrent->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                        style="padding-bottom: 10px;"></i>
                                </div>
                            </a>
                        @endif
                        <div class="text-center" style="padding-top: 5px;">
                            <span class="label label-success" data-toggle="tooltip"
                                data-original-title="@lang('torrent.type')">
                                {{ $torrent->type }}
                            </span>
                        </div>
                    </td>
    
                    <td>
                        <a class="view-torrent" href="{{ route('torrent', ['id' => $torrent->id]) }}">
                            {{ $torrent->name }}
                        </a>
    
                        @if (config('torrent.download_check_page') == 1)
                            <a href="{{ route('download_check', ['id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('common.download')">
                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                </button>
                            </a>
                        @else
                            <a href="{{ route('download', ['id' => $torrent->id]) }}">
                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('common.download')">
                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                </button>
                            </a>
                        @endif
    
                        <span data-toggle="tooltip" data-original-title="Bookmark" id="torrentBookmark{{ $torrent->id }}"
                            torrent="{{ $torrent->id }}"
                            state="{{ $bookmarks->where('torrent_id', $torrent->id)->first() ? 1 : 0 }}"
                            class="torrentBookmark"></span>
    
                        @if ($current = $user->history->where('info_hash', $torrent->info_hash)->first())
                            @if ($current->seeder == 1 && $current->active == 1)
                                <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.currently-seeding')!">
                                    <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                </button>
                            @endif
        
                            @if ($current->seeder == 0 && $current->active == 1)
                                <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.currently-leeching')!">
                                    <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                </button>
                            @endif
        
                            @if ($current->seeder == 0 && $current->active == 0 && $current->completed_at == null)
                                <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.not-completed')!">
                                    <i class="{{ config('other.font-awesome') }} fa-spinner"></i>
                                </button>
                            @endif
        
                            @if ($current->seeder == 0 && $current->active == 0 && $current->completed_at != null)
                                <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.completed-not-seeding')!">
                                    <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                </button>
                            @endif
                        @endif
    
                        <br>
                        @if ($torrent->anon == 1)
                            <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.uploader')"></i> @lang('common.anonymous')
                                @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                    <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                        ({{ $torrent->user->username }})
                                    </a>
                                @endif
                            </span>
                        @else
                            <span class="badge-extra text-bold">
                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.uploader')"></i>
                                <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
                                    {{ $torrent->user->username }}
                                </a>
                            </span>
                        @endif
    
                        @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                            @if ($user->ratings == 1)
                                <a href="https://www.imdb.com/title/tt{{ $torrent->imdb }}" target="_blank">
                                    <span class="badge-extra text-bold">
                                        <span class="text-gold movie-rating-stars">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                                data-original-title="@lang('torrent.view-more')"></i>
                                        </span>
                                        {{ $meta->imdbRating ?? '0' }}/10 ({{ $meta->imdbVotes ?? '0' }} @lang('torrent.votes'))
                                    </span>
                                </a>
                            @else
            
                                @if ($torrent->category->tv_meta)
                                    <a href="https://www.themoviedb.org/tv/{{ $torrent->tmdb }}?language={{ config('app.locale') }}"
                                        target="_blank">
                                    @endif
                                    @if ($torrent->category->movie_meta)
                                        <a href="https://www.themoviedb.org/movie/{{ $torrent->tmdb }}?language={{ config('app.locale') }}"
                                            target="_blank">
                                        @endif
            
                                        <span class="badge-extra text-bold">
                                            <span class="text-gold movie-rating-stars">
                                                <i class="{{ config('other.font-awesome') }} fa-thumbs-up" data-toggle="tooltip"
                                                    data-original-title="@lang('torrent.view-more')"></i>
                                            </span>
                                            {{ $meta->tmdbRating ?? '0' }}/10 ({{ $meta->tmdbVotes ?? '0' }} @lang('torrent.votes'))
                                        </span>
                                    </a>
                                @endif
                            @endif
    
                            @if ($torrent->category->game_meta && isset($meta))
                                <a href="{{ $meta->url }}" title="IMDB" target="_blank">
                                    <span class="badge-extra text-bold">@lang('torrent.rating'):
                                        <span class="text-gold movie-rating-stars">
                                            <i class="{{ config('other.font-awesome') }} fa-star"></i>
                                        </span>
                                        {{ $meta->rating }}/100 ({{ $meta->rating_count }} @lang('torrent.votes'))
                                    </span>
                                </a>
                            @endif
    
                            <span class="badge-extra text-bold text-pink">
                                <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip"
                                    data-original-title="@lang('torrent.thanks-given')"></i>
                                {{ $torrent->thanks_count }}
                            </span>
    
                            <a href="{{ route('torrent', ['id' => $torrent->id, 'hash' => '#comments']) }}">
                                <span class="badge-extra text-bold text-green">
                                    <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip"
                                        data-original-title="@lang('common.comments')"></i>
                                    {{ $torrent->comments_count }}
                                </span>
                            </a>
    
                            @if ($torrent->internal == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-magic' data-toggle='tooltip' title=''
                                        data-original-title='@lang(' torrent.internal-release')' style="color: #baaf92;"></i>
                                    @lang('torrent.internal')
                                </span>
                            @endif
    
                            @if ($torrent->stream == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-play text-red' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.stream-optimized')'></i>
                                    @lang('torrent.stream-optimized')
                                </span>
                            @endif
    
                            @if ($torrent->featured == 0)
                                @if ($torrent->doubleup == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config('other.font-awesome') }} fa-gem text-green' data-toggle='tooltip'
                                            title='' data-original-title='@lang(' torrent.double-upload')'></i>
                                        @lang('torrent.double-upload')
                                    </span>
                                @endif
                                @if ($torrent->free == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config('other.font-awesome') }} fa-star text-gold' data-toggle='tooltip'
                                            title='' data-original-title='@lang(' torrent.freeleech')'></i>
                                        @lang('torrent.freeleech')
                                    </span>
                                @endif
                            @endif
    
                            @if ($personal_freeleech)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-id-badge text-orange' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.personal-freeleech')'></i>
                                    @lang('torrent.personal-freeleech')
                                </span>
                            @endif
    
                            @if ($user->freeleechTokens->where('torrent_id', $torrent->id)->first())
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-star text-bold' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.freeleech-token')'></i>
                                    @lang('torrent.freeleech-token')
                                </span>
                            @endif
    
                            @if ($torrent->featured == 1)
                                <span class='badge-extra text-bold' style='background-image:url(/img/sparkels.gif);'>
                                    <i class='{{ config('other.font-awesome') }} fa-certificate text-pink' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.featured')'></i> @lang('torrent.featured')
                                </span>
                            @endif
    
                            @if ($user->group->is_freeleech == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-trophy text-purple' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.special-freeleech')'></i>
                                    @lang('torrent.special-freeleech')
                                </span>
                            @endif
    
                            @if (config('other.freeleech') == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-globe text-blue' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.global-freeleech')'></i>
                                    @lang('torrent.global-freeleech')
                                </span>
                            @endif
    
                            @if (config('other.doubleup') == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-globe text-green' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.global-double-upload')'></i>
                                    @lang('torrent.global-double-upload')
                                </span>
                            @endif
    
                            @if ($torrent->leechers >= 5)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-fire text-orange' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' common.hot')!'></i> @lang('common.hot')
                                </span>
                            @endif
    
                            @if ($torrent->sticky == 1)
                                <span class='badge-extra text-bold'>
                                    <i class='{{ config('other.font-awesome') }} fa-thumbtack text-black' data-toggle='tooltip'
                                        title='' data-original-title='@lang(' torrent.sticky')!'></i> @lang('torrent.sticky')
                                </span>
                            @endif
    
                            @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config('other.font-awesome') }} fa-magic text-black' data-toggle='tooltip'
                                            title='' data-original-title='@lang(' common.new')!'></i> @lang('common.new')
                                    </span>
                                @endif
    
                                @if ($torrent->highspeed == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config('other.font-awesome') }} fa-tachometer text-red'
                                            data-toggle='tooltip' title='' data-original-title='@lang('
                                            common.high-speeds')!'></i> @lang('common.high-speeds')
                                    </span>
                                @endif
    
                                @if ($torrent->sd == 1)
                                    <span class='badge-extra text-bold'>
                                        <i class='{{ config('other.font-awesome') }} fa-ticket text-orange'
                                            data-toggle='tooltip' title='' data-original-title='@lang('
                                            torrent.sd-content')!'></i> @lang('torrent.sd-content')
                                    </span>
                                @endif
    
                                <br>
    
                                @foreach($torrent->tags as $tag)
                                    <span class="badge-extra text-bold">
                                        <i class='{{ config('other.font-awesome') }} fa-tag' data-toggle='tooltip' title=''
                                            data-original-title='@lang(' torrent.genre')'></i> {{ $tag->name }}
                                    </span>
                                @endforeach
                    </td>
    
                    <td>
                        <time>{{ $torrent->created_at->diffForHumans() }}</time>
                    </td>
                    <td>
                        <span class='badge-extra text-blue text-bold'>{{ $torrent->getSize() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                            <span class='badge-extra text-green text-bold'>
                                {{ $torrent->seeders }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('peers', ['id' => $torrent->id]) }}">
                            <span class='badge-extra text-red text-bold'>
                                {{ $torrent->leechers }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('history', ['id' => $torrent->id]) }}">
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
