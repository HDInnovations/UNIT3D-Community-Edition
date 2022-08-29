<button
    class="votes__dislike"
    wire:click="store({{ $post->id }})"
    title="{{ __('forum.dislike-post') }}"
>
    @if(auth()->user()->likes()->where('post_id', '=', $post->id)->where('dislike', '=', 1)->first())
        <i class="votes__dislike-icon {{ config('other.font-awesome') }} fa-thumbs-down fa-beat"></i>
    @else
        <i class="votes__dislike-icon {{ config('other.font-awesome') }} fa-thumbs-down"></i>
    @endif
    <span class="votes__dislike-count">{{ $post->dislikes()->count() }}</span>
</button>
