@if ($this->isBookmarked)
    <button
        wire:click="destroy({{ $torrent->id }})"
        class="form__standard-icon-button"
        title="Unbookmark"
    >
        <i class="{{ config('other.font-awesome') }} fa-bookmark-slash"></i>
    </button>
@else
    <button
        wire:click="store({{ $torrent->id }})"
        class="form__standard-icon-button"
        title="Bookmark"
    >
        <i class="{{ config('other.font-awesome') }} fa-bookmark"></i>
    </button>
@endif
