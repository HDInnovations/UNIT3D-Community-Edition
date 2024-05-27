<div class="panel__body">
    <section class="recommendations" style="max-height: 330px !important">
        @switch(true)
            @case($torrent->category->movie_meta)
                @forelse ($meta->recommendedMovies ?? [] as $movie)
                    <x-movie.poster :$movie :categoryId="$torrent->category_id" />
                @empty
                    No Recommendations Found!
                @endforelse

                @break
            @case($torrent->category->tv_meta)
                @forelse ($meta->recommendedTv ?? [] as $tv)
                    <x-tv.poster :$tv :categoryId="$torrent->category_id" />
                @empty
                    No Recommendations Found!
                @endforelse

                @break
            @default
                No recommendations Found!
        @endswitch
    </section>
</div>
