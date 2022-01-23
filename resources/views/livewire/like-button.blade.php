<div style="display: inline;">
    <a wire:click="store({{ $post->id }})" class="text-green" data-toggle="tooltip" style="margin-right: 16px;"
       data-original-title="{{ __('forum.like-post') }}">
        <i class="icon-like {{ config('other.font-awesome') }} fa-thumbs-up fa-2x @if(auth()->user()->likes()->where('post_id', '=', $post->id)->where('like', '=', 1)->first()) fa-beat @endif"></i>
        <span class="count" style="font-size: 20px;">{{ $post->likes()->count() }}</span>
    </a>
</div>