<button
    class="votes__like"
    wire:click="store({{ $post->id }})"
    title="{{ __('forum.like-post') }}"
>
    @if($post->likes_exists)
        <i class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up fa-beat"></i>
    @else
        <i class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up"></i>
    @endif
    <span class="votes__like-count">{{ $post->likes_count }}</span>
</button>
