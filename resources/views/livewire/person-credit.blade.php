<section class="panelV2" x-data="{ tab: @entangle('occupationId') }">
    <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::CREATOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::CREATOR->value }}"
            x-show="{{ $createdCount }} > 0"
        >
            Creator ({{ $createdCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::DIRECTOR->value }}"
            x-show="{{ $directedCount }} > 0"
        >
            Director ({{ $directedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::WRITER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::WRITER->value }}"
            x-show="{{ $writtenCount }} > 0"
        >
            Writer ({{ $writtenCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::PRODUCER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::PRODUCER->value }}"
            x-show="{{ $producedCount }} > 0"
        >
            Producer ({{ $producedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::COMPOSER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::COMPOSER->value }}"
            x-show="{{ $composedCount }} > 0"
        >
            Composer ({{ $composedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::CINEMATOGRAPHER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::CINEMATOGRAPHER->value }}"
            x-show="{{ $cinematographedCount }} > 0"
        >
            Cinematographer ({{ $cinematographedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::EDITOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::EDITOR->value }}"
            x-show="{{ $editedCount }} > 0"
        >
            Editor ({{ $editedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::PRODUCTION_DESIGNER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::PRODUCTION_DESIGNER->value }}"
            x-show="{{ $productionDesignedCount }} > 0"
        >
            Production Designer ({{ $productionDesignedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::ART_DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::ART_DIRECTOR->value }}"
            x-show="{{ $artDirectedCount }} > 0"
        >
            Art Director ({{ $artDirectedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupations::ACTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupations::ACTOR->value }}"
            x-show="{{ $actedCount }} > 0"
        >
            Actor ({{ $actedCount }})
        </li>
    </menu>
    <div class="panel__body">
        @forelse ($medias as $media)
            @switch ($media->meta)
                @case('movie')
                    <x-movie.card :media="$media" :personalFreeleech="$personalFreeleech" />
                    @break
                @case('tv')
                    <x-tv.card :media="$media" :personalFreeleech="$personalFreeleech" />
                    @break
            @endswitch
        @empty
            No Media
        @endforelse
    </div>
</section>
