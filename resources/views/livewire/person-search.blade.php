<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.persons') }}</h2>
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
    {{ $persons->links('partials.pagination') }}
    <div class="panel__body">
        @forelse ($persons as $person)
            <div class="col-md-2 text-center">
                <div class="thumbnail" style="min-height: 315px;">
                    <a href="{{ route('mediahub.persons.show', ['id' => $person->id]) }}">
                        <img alt="{{ $person->name }}"
                             src="{{ isset($person->still) ? tmdb_image('cast_mid', $person->still) : 'https://via.placeholder.com/160x240' }}">
                    </a>
                    <div class="caption">
                        <p class="text-bold">{{ $person->name }}</p>
                    </div>
                </div>
            </div>
        @empty
            No persons.
        @endforelse
    </div>
    {{ $persons->links('partials.pagination') }}
</section>


