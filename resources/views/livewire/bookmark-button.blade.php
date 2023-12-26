@if ($this->isBookmarked)
    <button
        wire:click="destroy({{ $torrent->id }})"
        class="form__button form__button--outlined form__button--centered"
    >
        <i class="{{ config('other.font-awesome') }} fa-bookmark-slash"></i>
        {{ __('torrent.unbookmark') }}
    </button>
@else
    <button
        wire:click="store({{ $torrent->id }})"
        class="form__button form__button--outlined form__button--centered"
    >
        <i class="{{ config('other.font-awesome') }} fa-bookmark"></i>
        {{ __('torrent.bookmark') }}
    </button>
@endif
