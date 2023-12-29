<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('mediahub.persons') }}</h2>
        <div class="panel__actions">
            <div class="panel__action">
                <div class="form__group">
                    <input
                        id="name"
                        class="form__text"
                        placeholder=" "
                        type="text"
                        wire:model.debounce.250ms="search"
                    />
                    <label class="form__label form__label--floating" for="name">
                        {{ __('torrent.search-by-name') }}
                    </label>
                </div>
            </div>
        </div>
    </header>
    {{ $persons->links('partials.pagination') }}
    <div
        class="panel__body"
        style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 2rem;
        "
    >
        @forelse ($persons as $person)
            <figure style="display: flex; flex-direction: column; align-items: center">
                <a href="{{ route('mediahub.persons.show', ['id' => $person->id]) }}">
                    <img
                        alt="{{ $person->name }}"
                        src="{{ isset($person->still) ? tmdb_image('cast_mid', $person->still) : 'https://via.placeholder.com/160x240' }}"
                        style="width: 140px; height: 140px; object-fit: cover; border-radius: 50%"
                    />
                </a>
                <figcaption>{{ $person->name }}</figcaption>
            </figure>
        @empty
            No persons.
        @endforelse
    </div>
    {{ $persons->links('partials.pagination') }}
</section>
