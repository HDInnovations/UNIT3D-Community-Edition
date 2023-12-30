<button
    class="votes__dislike"
    wire:click="store({{ $post->id }})"
    title="{{ __('forum.dislike-post') }}"
>
    @if ($post->dislikes_exists)
        <i
            class="votes__dislike-icon {{ config('other.font-awesome') }} fa-thumbs-down post__like-animation"
        ></i>
    @else
        <i class="votes__dislike-icon {{ config('other.font-awesome') }} fa-thumbs-down"></i>
    @endif
    <span class="votes__dislike-count">{{ $dislikesCount }}</span>
</button>
