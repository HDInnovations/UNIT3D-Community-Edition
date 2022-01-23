<div class="panel-body" style="padding: 5px;">
    @if (! empty($meta->collection['0']) && $torrent->category->movie_meta)
        <div id="collection_waypoint" class="collection">
            <div class="header collection"
                 @php $backdrop = $meta->collection['0']->backdrop @endphp
                 style=" background-image: url({{ isset($backdrop) ? tmdb_image('back_big', $backdrop) : 'https://via.placeholder.com/1280x300' }}); background-size: cover; background-position: 50% 50%;">
                <div class="collection-overlay"
                     style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: linear-gradient(rgba(0, 0, 0, 0.87), rgba(45, 71, 131, 0.46));"></div>
                <section class="collection">
                    <h2>Part of the {{ $meta->collection['0']->name }}</h2>
                    <p class="text-blue">Includes:
                        @foreach($meta->collection['0']->movie as $collection_movie)
                            {{ $collection_movie->title }},
                        @endforeach
                    </p>

                    <a href="{{ route('mediahub.collections.show', ['id' => $meta->collection['0']->id]) }}"
                       role="button" class="btn btn-labeled btn-primary"
                       style=" margin: 0; text-transform: uppercase; position: absolute; bottom: 50px;">
				    								<span class="btn-label">
				    									<i class="{{ config("other.font-awesome") }} fa-copy"></i> View The Collection
				    								</span>
                    </a>
                </section>
            </div>
        </div>
    @else
        <div class="text-center">
            <h4 class="text-bold text-danger">
                <i class="{{ config('other.font-awesome') }} fa-frown"></i> No Collection Found!
            </h4>
        </div>
    @endif
</div>