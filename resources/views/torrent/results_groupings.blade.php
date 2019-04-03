<div style="width: 100% !important; display: table !important;">
    <div class="mb-5" style="width: 100% !important; display: table-cell !important;">
        @if($torrents && is_array($torrents))
            @foreach ($torrents as $k => $c)
                @php
                    $parent = reset($c);
                    $t = $parent['chunk'];
                @endphp
                <div class="box">
                    <div class="card is-group is-torrent">
                        <div class="card_head">
                            @if($attributes && is_array($attributes))
                                <span class="badge-user text-bold" style="float:right;">
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $attributes[$k]['seeders'] }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $attributes[$k]['leechers'] }} /
                                    <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $attributes[$k]['times_completed'] }}
                                </span>&nbsp;
                            @endif
                                <span class="badge-user text-bold" style="float: right;">
                                                <i class="{{ config('other.font-awesome') }} fa-star text-gold"></i>
                                                @if($t->movie && ($t->movie->imdbRating || $t->movie->tmdbVotes))
                                        @if ($user->ratings == 1)
                                            {{ $t->movie->imdbRating }}/10 ({{ $t->movie->imdbVotes }} @lang('torrent.votes'))
                                        @else
                                            {{ $t->movie->tmdbRating }}/10 ({{ $t->movie->tmdbVotes }} @lang('torrent.votes'))
                                        @endif
                                    @endif
                                            </span>
                        </div>
                        <div class="card_alt">
                            <div class="body_poster">
                                @if($t->movie && $t->movie->poster)
                                    <img src="{{ $t->movie->poster }}" class="show-poster" data-image='<img src="{{ $t->movie->poster }}" alt="@lang('torrent.poster')" style="height: 1000px;">'>
                                @else
                                    <img src="https://via.placeholder.com/600x900" />
                                @endif
                            </div>
                            <div class="body_grouping" style="width: 100%;">
                                <h3 class="description_title">
                                    {{ ($t->movie->title ? $t->movie->title : $t->name) }}
                                    @if($t->movie && $t->movie->releaseYear)
                                        <span class="text-bold text-pink"> {{ $t->movie->releaseYear }}</span>
                                    @endif
                                </h3>
                                @if ($t->movie && $t->movie->genres)
                                    @foreach ($t->movie->genres as $genre)
                                        <span class="genre-label">{{ $genre }}</span>
                                    @endforeach
                                @endif
                                <p class="description_plot" style="width: 100%;">
                                    @if($t->movie && $t->movie->plot)
                                        {{ $t->movie->plot }}
                                    @endif
                                </p>
                                <div class="card_holder" style="width: 100%;" ;>
                                    <hr style="padding: 0 !important; margin: 5px 0 !important; width: 100%;">
                                    <div style="padding: 10px 0; margin: auto;">
                                        <div class="table-responsive">
                                            <table class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom: 5px !important;">
                                                <thead>
                                                <tr>
                                                    <th>@lang('torrent.category')</th>
                                                    <th>@lang('torrent.name')</th>
                                                    <th><i class="{{ config('other.font-awesome') }} fa-clock"></i></th>
                                                    <th><i class="{{ config('other.font-awesome') }} fa-file"></i></th>
                                                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-up"></i></th>
                                                    <th><i class="{{ config('other.font-awesome') }} fa-arrow-circle-down"></i></th>
                                                    <th><i class="{{ config('other.font-awesome') }} fa-check-square"></i></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $class = "";
                                                    $hidden = "";
                                                    $attr = "";
                                                @endphp
                                            @for($x = 0; $x<count($c); $x++)
                                                @php
                                                    if(array_key_exists('torrent'.$x,$c)) {
                                                        $current = $c['torrent'.$x]['chunk'];
                                                    } else {
                                                        continue;
                                                    }
                                                    if($x > 5) {
                                                        $class=" facetedLoading";
                                                        $hidden="display: none;";
                                                        $attr = $k;
                                                    }
                                            @endphp
                                            @if ($current->sticky == 1)
                                                <tr class="success{{ $class }}" style="{{ $hidden }}" torrent="{{ $attr }}">
                                            @else
                                                <tr class="{{ $class }}" style="{{ $hidden }}" torrent="{{ $attr }}">
                                                    @endif
                                                    <td>
                                                        <a href="{{ route('category', ['slug' => $current->category->slug, 'id' => $current->category->id]) }}">
                                                            <div class="text-center">
                                                                <i class="{{ $current->category->icon }} torrent-icon" data-toggle="tooltip"
                                                                   data-original-title="{{ $current->category->name }} {{ strtolower(trans('torrent.torrent')) }}"
                                                                   style="padding-bottom: 6px;"></i>
                                                            </div>
                                                        </a>
                                                        <div class="text-center">
                                                            <span class="label label-success" data-toggle="tooltip" data-original-title="@lang('torrent.type')">
                                                                {{ $current->type }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class="view-torrent" href="{{ route('torrent', ['slug' => $current->slug, 'id' => $current->id]) }}">
                                                            {{ $current->name }}
                                                        </a>
                                                        @if (config('torrent.download_check_page') == 1)
                                                            <a href="{{ route('download_check', ['slug' => $current->slug, 'id' => $current->id]) }}">
                                                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                                        data-original-title="@lang('common.download')">
                                                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('download', ['slug' => $current->slug, 'id' => $current->id]) }}">
                                                                <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                                        data-original-title="@lang('common.download')">
                                                                    <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                                                </button>
                                                            </a>
                                                        @endif

                                                        <span data-toggle="tooltip" data-original-title="Bookmark" id="torrentBookmark{{ $current->id }}" torrent="{{ $current->id }}" state="{{ $current->bookmarked() ? 1 : 0}}" class="torrentBookmark"></span>

                                                        <br>
                                                        @if ($current->anon == 1)
                                                            <span class="badge-extra text-bold">
                                                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i> @lang('common.anonymous')
                                                                @if ($user->id == $current->user->id || $user->group->is_modo)
                                                                    <a href="{{ route('profile', ['username' => $current->user->username, 'id' => $current->user->id]) }}">
                                                                        ({{ $current->user->username }})
                                                                    </a>
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="badge-extra text-bold">
                                                                <i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip" data-original-title="@lang('torrent.uploader')"></i>
                                                                    <a href="{{ route('profile', ['username' => $current->user->username, 'id' => $current->user->id]) }}">
                                                                        {{ $current->user->username }}
                                                                    </a>
                                                            </span>
                                                        @endif
                                                                        <span class="badge-extra text-bold text-pink">
                            <i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip" data-original-title="@lang('torrent.thanks-given')"></i>
                                                        {{ $current->thanks_count }}
                        </span>

                                                                        <span class="badge-extra text-bold text-green">
                            <i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip" data-original-title="@lang('common.comments')"></i>
                                                        {{ $current->comments_count }}
                        </span>

                                                                        @if ($current->internal == 1)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.internal-release')' style="color: #BAAF92;"></i> @lang('torrent.internal')
                            </span>
                                                                        @endif
                                                                        @if ($current->stream == 1)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-play text-red' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.stream-optimized')'></i> @lang('torrent.stream-optimized')
                            </span>
                                                                        @endif

                                                                        @if ($current->featured == 0)
                                                                            @if ($current->doubleup == 1)
                                                                                <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-gem text-green' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.double-upload')'></i> @lang('torrent.double-upload')
                            </span>
                                                                            @endif
                                                                            @if ($current->free == 1)
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

                                                                        @php $freeleech_token = \App\Models\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $current->id)->first(); @endphp
                                                                        @if ($freeleech_token)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-coins text-bold' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.freeleech-token')'></i> @lang('torrent.freeleech-token')
                            </span>
                                                                        @endif

                                                                        @if ($current->featured == 1)
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
                                   data-original-title='@lang('torrent.double-upload')'></i> @lang('torrent.double-upload')
                            </span>
                                                                        @endif

                                                                        @if ($current->leechers >= 5)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-fire text-orange' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.hot')!'></i> @lang('common.hot')!
                            </span>
                                                                        @endif

                                                                        @if ($current->sticky == 1)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-thumbtack text-black' data-toggle='tooltip' title=''
                                   data-original-title='@lang('torrent.sticky')!'></i> @lang('torrent.sticky')
                            </span>
                                                                        @endif

                                                                        @if ($user->updated_at->getTimestamp() < $current->created_at->getTimestamp())
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-magic text-black' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.new')!'></i> @lang('common.new')
                            </span>
                                                                        @endif

                                                                        @if ($current->highspeed == 1)
                                                                            <span class='badge-extra text-bold'>
                                <i class='{{ config("other.font-awesome") }} fa-tachometer text-red' data-toggle='tooltip' title=''
                                   data-original-title='@lang('common.high-speeds')'></i> @lang('common.high-speeds')
                            </span>
                                                                        @endif

                                                                        @if ($current->sd == 1)
                                                                            <span class='badge-extra text-bold'>
                                                                            <i class='{{ config("other.font-awesome") }} fa-ticket text-orange' data-toggle='tooltip' title=''
                                                                               data-original-title='@lang('torrent.sd-content')!'></i> @lang('torrent.sd-content')
                                                                            </span>
                                                                        @endif
                                                    </td>
                                                    <td>
                                                        <time>{{ $current->created_at->diffForHumans() }}</time>
                                                    </td>
                                                    <td>
                                                        <span class='badge-extra text-blue text-bold'>{{ $current->getSize() }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('peers', ['slug' => $current->slug, 'id' => $current->id]) }}">
                            <span class='badge-extra text-green text-bold'>
                                {{ $current->seeders }}
                            </span>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('peers', ['slug' => $current->slug, 'id' => $current->id]) }}">
                            <span class='badge-extra text-red text-bold'>
                                {{ $current->leechers }}
                            </span>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('history', ['slug' => $current->slug, 'id' => $current->id]) }}">
                            <span class='badge-extra text-orange text-bold'>
                                {{ $current->times_completed }} @lang('common.times')
                            </span>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endfor
                                                @if($x >= 5)
                                                    <tr id="facetedCell{{ $k }}">
                                                        <td colspan="7" class="text-center">
                                                            <a href="#/" class="facetedLoader" torrent="{{ $k }}">
                            <span class='btn btn-sm btn-warning'>
                                Load All {{ $x }} Torrents
                            </span>
                                                        </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card_footer">
                            <div style="float: right;">
                                @if($attributes && is_array($attributes))
                                    @php $cats = $repository->categories()->toArray(); @endphp
                                    @foreach($attributes[$k]['types'] as $a => $attr)
                                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $attr }}</span>&nbsp;
                                    @endforeach
                                    @foreach($attributes[$k]['categories'] as $a => $attr)
                                        @if(array_key_exists($attr,$cats))
                                            <span class="badge-user text-bold text-blue" style="float:right;">{{ $cats[$attr] }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<div style="width: 100% !important; display: table !important;">
    <div class="align-center" style="width: 100% !important; display: table-cell !important;">
        @if($links)
            {{ $links->links() }}
        @endif
    </div>
</div>