<button
    class="votes__like"
    wire:click="store({{ $post->id }})"
    title="{{ __('forum.like-post') }}"
>
    @if ($post->likes_exists)
        <i
            class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up post__like-animation"
        ></i>
    @else
        <i class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up"></i>
    @endif
    <span class="votes__like-count">{{ $likesCount }}</span>
</button>
