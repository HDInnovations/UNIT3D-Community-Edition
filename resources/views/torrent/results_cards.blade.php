<div style="width: 100% !important; display: table !important;">
    <div class="align-center" style="display: table-cell !important; width: 100% !important;">{{ $torrents->links() }}</div>
</div>
<div style="width: 100% !important; display: table !important;">
    <div class="mb-5" style="display: table-cell !important; width: 100% !important;">
        @foreach ($torrents as $k => $t)
            <div class="col-md-4">
                <div class="card is-torrent">
                    <div class="card_head">
                    <span class="badge-user text-bold" style="float:right;">
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-up text-green"></i> {{ $t->seeders }} /
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-arrow-down text-red"></i> {{ $t->leechers }} /
                        <i class="{{ config('other.font-awesome') }} fa-fw fa-check text-orange"></i>{{ $t->times_completed }}
                    </span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->getSize() }}</span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->type }}</span>&nbsp;
                        <span class="badge-user text-bold text-blue" style="float:right;">{{ $t->category->name }}</span>&nbsp;
                    </div>
                    <div class="card_body">
                        <div class="body_poster">
                            @if($t->meta && $t->meta->poster)
                                <img src="{{ $t->meta->poster }}" class="show-poster" data-image='<img src="{{ $t->meta->poster }}" alt="@lang('torrent.poster')" style="height: 1000px;">'>
                            @endif
                        </div>
                        <div class="body_description">
                            <h3 class="description_title">
                                <a href="{{ route('torrent', ['slug' => $t->slug, 'id' => $t->id]) }}">{{ $t->name }}
                                    @if($t->meta && $t->meta->releaseYear)
                                        <span class="text-bold text-pink"> {{ $t->meta->releaseYear }}</span>
                                    @endif
                                </a>
                            </h3>
                            @if ($t->meta && $t->meta->genres)
                                @foreach ($t->meta->genres as $genre)
                                    <span class="genre-label">{{ $genre }}</span>
                                @endforeach
                            @endif
                            <p class="description_plot">
                                @if($t->meta && $t->meta->plot)
                                    {{ $t->meta->plot }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="card_footer">
                        <div style="float: left;">
                            @if ($t->anon == 1)
                                <span class="badge-user text-orange text-bold">{{ strtoupper(trans('common.anonymous')) }} @if (auth()->user()->id == $t->user->id || auth()->user()->group->is_modo)
                                        <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">({{ $t->user->username }}
                                                    )</a>@endif</span>
                            @else
                                <a href="{{ route('profile', ['username' => $t->user->username, 'id' => $t->user->id]) }}">
                                <span class="badge-user text-bold" style="color:{{ $t->user->group->color }}; background-image:{{ $t->user->group->effect }};">
                                    <i class="{{ $t->user->group->icon }}" data-toggle="tooltip" title=""
                                       data-original-title="{{ $t->user->group->name }}"></i> {{ $t->user->username }}
                                </span>
                                </a>
                            @endif
                        </div>
                        <span class="badge-user text-bold" style="float: right;">
                        <i class="{{ config('other.font-awesome') }} fa-thumbs-up text-gold"></i>
                        @if($t->meta && ($t->meta->imdbRating || $t->meta->tmdbVotes))
                                @if ($user->ratings == 1)
                                    {{ $t->meta->imdbRating }}/10 ({{ $t->meta->imdbVotes }} @lang('torrent.votes'))
                                @else
                                    {{ $t->meta->tmdbRating }}/10 ({{ $t->meta->tmdbVotes }} @lang('torrent.votes'))
                                @endif
                            @endif
                    </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div style="width: 100% !important; display: table !important;">
    <div class="align-center" style="display: table-cell !important; width: 100% !important;">{{ $torrents->links() }}</div>
</div>
