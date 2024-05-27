<button
    @if ($this->isBookmarked)
        wire:click="destroy({{ $torrent->id }})"
        class="form__standard-icon-button"
        title="Unbookmark"
    @else
        wire:click="store({{ $torrent->id }})"
        class="form__standard-icon-button"
        title="Bookmark"
    @endif
>
    @if ($this->isBookmarked)
        <i class="{{ config('other.font-awesome') }} fa-bookmark-slash"></i>
    @else
        <i class="{{ config('other.font-awesome') }} fa-bookmark"></i>
    @endif
</button>
