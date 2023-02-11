<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.networks') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        class="form__text"
                        placeholder=""
                        type="text"
                        wire:model.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating">
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $networks->links('partials.pagination') }}
    <div class="panel__body blocks">
        @forelse ($networks as $network)
            <a href="{{ route('mediahub.networks.show', ['id' => $network->id]) }}" style="padding: 0 2px;">
                <div class="general media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                    <h2 class="text-bold">
                        @if(isset($network->logo))
                            <img src="{{ tmdb_image('logo_mid', $network->logo) }}"
                                 style="max-height: 100px; max-width: 300px; width: auto;" alt="{{ $network->name }}">
                        @else
                            {{ $network->name }}
                        @endif
                    </h2>
                    <span></span>
                    <h2 style="font-size: 14px;"><i
                                class="{{ config('other.font-awesome') }} fa-tv-retro"></i> {{ $network->tv_count }}
                        Shows</h2>
                </div>
            </a>
        @empty
            No {{ __('mediahub.networks') }}
        @endforelse
    </div>
    {{ $networks->links('partials.pagination') }}
</div>
