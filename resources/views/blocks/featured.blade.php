@if ($featured)
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading featured-header">
                <h4><i class="{{ config('other.font-awesome') }} fa-star"></i> {{ __('blocks.featured-torrents') }}</h4>
            </div>
            <div x-data>
                <ul
                    class="featured-carousel"
                    x-ref="featured"
                    x-init="setInterval(function () {$el.parentNode.matches(':hover') ? null : (($el.scrollLeft == $el.scrollWidth - $el.offsetWidth - 16) ? $el.scrollLeft = 0 : $el.scrollLeft += (($el.children[0].offsetWidth + 16) / 2 + 1)) }, 5000)"
                >
                    @foreach ($featured as $feature)
                        @if ($feature->torrent === null || ! $feature->torrent->isApproved())
                            @continue
                        @endif
                        @php
                            $meta = match(1) {
                                $feature->torrent->category->tv_meta => App\Models\Tv::query()->with('genres', 'networks', 'seasons')->where('id', '=', $feature->torrent->tmdb ?? 0)->first(),
                                $feature->torrent->category->movie_meta => App\Models\Movie::query()->with('genres', 'cast', 'companies', 'collection')->where('id', '=', $feature->torrent->tmdb ?? 0)->first(),
                                $feature->torrent->category->game_meta => MarcReichel\IGDBLaravel\Models\Game::query()->with(['artworks' => ['url', 'image_id'], 'genres' => ['name']])->find((int) $feature->torrent->igdb),
                                default => null,
                            };
                        @endphp
                        <li class="featured-carousel__slide">
                            <x-torrent.card :meta="$meta" :torrent="$feature->torrent" />
                            <footer class="featured-carousel__feature-details">
                                <p class="featured-carousel__featured-until">
                                    {{ __('blocks.featured-until') }}:<br>
                                    <time datetime="{{ $feature->created_at->addDay(7) }}">
                                        {{ $feature->created_at->addDay(7)->toFormattedDateString() }}
                                        ({{ $feature->created_at->addDay(7)->diffForHumans() }}!)
                                    </time>
                                </p>
                                <p class="featured-carousel__featured-by">
                                    {{ __('blocks.featured-by') }}: {{ $feature->user->username }}!
                                </p>
                            </footer>
                        </li>
                    @endforeach
                </ul>
                <nav class="featured-carousel__nav">
                    <button class="featured-carousel__previous" x-on:click="$refs.featured.scrollLeft == 0 ? $refs.featured.scrollLeft = $refs.featured.scrollWidth : $refs.featured.scrollLeft -= (($refs.featured.children[0].offsetWidth + 16) / 2 + 1)">
                        <i class="{{ \config('other.font-awesome') }} fa-angle-left"></i>
                    </button>
                    <button class="featured-carousel__next" x-on:click="$refs.featured.scrollLeft == ($refs.featured.scrollWidth - $refs.featured.offsetWidth) ? $refs.featured.scrollLeft = 0 : $refs.featured.scrollLeft += (($refs.featured.children[0].offsetWidth + 16) / 2 + 1)">
                        <i class="{{ \config('other.font-awesome') }} fa-angle-right"></i>
                    </button>
                </nav>
            </div>
        </div>
    </div>
@endif
