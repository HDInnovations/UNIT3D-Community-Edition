<div class="container-fluid">
    @include('torrent.partials.search')
    <br>
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <div class="form__group">
                        <select
                                class="form__select"
                                wire:model="perPage"
                                required
                        >
                            <option>25</option>
                            <option>50</option>
                            <option>75</option>
                            <option>100</option>
                        </select>
                        <label class="form__label form__label--floating">
                            {{ __('common.quantity') }}
                        </label>
                    </div>
                </div>
            </div>
        </header>
        {{ $medias->links('partials.pagination') }}
        <div class="panel__body torrent-search--grouped__results">
            @foreach ($medias as $media)
                @switch ($media->meta)
                    @case('movie')
                        <x-movie.card :media="$media" :personalFreeleech="$personalFreeleech" />
                        @break
                    @case('tv')
                        <x-tv.card :media="$media" :personalFreeleech="$personalFreeleech" />
                        @break
                @endswitch
            @endforeach
        </div>
        {{ $medias->links('partials.pagination') }}
    </section>
</div>
