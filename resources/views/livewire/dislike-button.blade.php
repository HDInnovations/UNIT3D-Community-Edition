<div style="display: inline;">
    <a wire:click="store({{ $post->id }})" class="text-red" data-toggle="tooltip"
       data-original-title="{{ __('forum.dislike-post') }}">
        <i class="icon-dislike {{ config('other.font-awesome') }} fa-thumbs-down fa-2x @if(auth()->user()->likes()->where('post_id', '=', $post->id)->where('dislike', '=', 1)->first()) fa-beat @endif"></i>
        <span class="count" style="font-size: 20px;">{{ $post->dislikes()->count() }}</span>
    </a>
</div>
