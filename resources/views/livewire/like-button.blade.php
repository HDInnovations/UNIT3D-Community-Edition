<button
    class="votes__like"
    wire:click="store({{ $post->id }})"
    title="{{ __('forum.like-post') }}"
>
    @if(auth()->user()->likes()->where('post_id', '=', $post->id)->where('like', '=', 1)->first())
        <i class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up fa-beat"></i>
    @else
        <i class="votes__like-icon {{ config('other.font-awesome') }} fa-thumbs-up"></i>
    @endif
    <span class="votes__like-count">{{ $post->likes()->count() }}</span>
</button>
