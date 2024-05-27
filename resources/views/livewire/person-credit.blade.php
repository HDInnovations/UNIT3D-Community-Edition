<section class="panelV2" x-data="{ tab: @entangle('occupationId').live }">
    <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::CREATOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::CREATOR->value }}"
            x-show="{{ $createdCount }} > 0"
        >
            Creator ({{ $createdCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::DIRECTOR->value }}"
            x-show="{{ $directedCount }} > 0"
        >
            Director ({{ $directedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::WRITER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::WRITER->value }}"
            x-show="{{ $writtenCount }} > 0"
        >
            Writer ({{ $writtenCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::PRODUCER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::PRODUCER->value }}"
            x-show="{{ $producedCount }} > 0"
        >
            Producer ({{ $producedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::COMPOSER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::COMPOSER->value }}"
            x-show="{{ $composedCount }} > 0"
        >
            Composer ({{ $composedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::CINEMATOGRAPHER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::CINEMATOGRAPHER->value }}"
            x-show="{{ $cinematographedCount }} > 0"
        >
            Cinematographer ({{ $cinematographedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::EDITOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::EDITOR->value }}"
            x-show="{{ $editedCount }} > 0"
        >
            Editor ({{ $editedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::PRODUCTION_DESIGNER->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::PRODUCTION_DESIGNER->value }}"
            x-show="{{ $productionDesignedCount }} > 0"
        >
            Production Designer ({{ $productionDesignedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::ART_DIRECTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::ART_DIRECTOR->value }}"
            x-show="{{ $artDirectedCount }} > 0"
        >
            Art Director ({{ $artDirectedCount }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === {{ App\Enums\Occupation::ACTOR->value }} && 'panel__tab--active'"
            x-cloak
            x-on:click="tab = {{ App\Enums\Occupation::ACTOR->value }}"
            x-show="{{ $actedCount }} > 0"
        >
            Actor ({{ $actedCount }})
        </li>
    </menu>
    <div class="panel__body">
        @forelse ($medias as $media)
            @switch($media->meta)
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
