<div class="panel__body" style="padding: 5px">
    @if (! empty($meta->collection['0']) && $torrent->category->movie_meta)
        <article
            class="collection"
            style="
                background-image: linear-gradient(rgba(0, 0, 0, 0.87), rgba(45, 71, 131, 0.46)),
                    url({{ isset($meta->collection['0']->backdrop) ? tmdb_image('back_big', $meta->collection['0']->backdrop) : 'https://via.placeholder.com/1280x300' }});
            "
        >
            <h3 class="collection__heading">
                <a
                    class="collection__link"
                    href="{{ route('mediahub.collections.show', ['id' => $meta->collection['0']->id]) }}"
                >
                    {{ $meta->collection['0']->name }}
                </a>
            </h3>
            <p class="collection__description">
                {{ __('mediahub.includes') }}
                {{ $meta->collection['0']->movie->pluck('title')->implode(',') }}
            </p>
        </article>
    @else
        <div class="text-center">
            <h4 class="text-bold text-danger">
                <i class="{{ config('other.font-awesome') }} fa-frown"></i>
                No Collection Found!
            </h4>
        </div>
    @endif
</div>
