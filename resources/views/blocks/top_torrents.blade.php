<section class="panelV2" x-data="{ tab: 'newest' }">
    <h2 class="panel__heading">{{ __('blocks.top-torrents') }}</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'newest' && 'panel__tab--active'"
            x-on:click="tab = 'newest'"
        >
            {{ __('blocks.new-torrents') }}
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'seeded' && 'panel__tab--active'"
            x-on:click="tab = 'seeded'"
        >
            {{ __('torrent.top-seeded') }}
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'leeched' && 'panel__tab--active'"
            x-on:click="tab = 'leeched'"
        >
            {{ __('torrent.top-leeched') }}
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'dying' && 'panel__tab--active'"
            x-on:click="tab = 'dying'"
        >
            {{ __('torrent.dying-torrents') }}
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'dead' && 'panel__tab--active'"
            x-on:click="tab = 'dead'"
        >
            {{ __('torrent.dead-torrents') }}
        </li>
    </menu>
    <div class="data-table-wrapper" x-show="tab === 'newest'">
        <table class="data-table">
            <tbody>
                @foreach ($newest as $torrent)
                    <x-torrent.row
                        :$torrent
                        :meta="$torrent->meta"
                        :personalFreeleech="$personal_freeleech"
                    />
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="data-table-wrapper" x-cloak x-show="tab === 'seeded'">
        <table class="data-table">
            <tbody>
                @foreach ($seeded as $torrent)
                    <x-torrent.row
                        :$torrent
                        :meta="$torrent->meta"
                        :personalFreeleech="$personal_freeleech"
                    />
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="data-table-wrapper" x-cloak x-show="tab === 'leeched'">
        <table class="data-table">
            <tbody>
                @foreach ($leeched as $torrent)
                    <x-torrent.row
                        :$torrent
                        :meta="$torrent->meta"
                        :personalFreeleech="$personal_freeleech"
                    />
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="data-table-wrapper" x-cloak x-show="tab === 'dying'">
        <table class="data-table">
            <tbody>
                @foreach ($dying as $torrent)
                    <x-torrent.row
                        :$torrent
                        :meta="$torrent->meta"
                        :personalFreeleech="$personal_freeleech"
                    />
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="data-table-wrapper" x-cloak x-show="tab === 'dead'">
        <table class="data-table">
            <tbody>
                @foreach ($dead as $torrent)
                    <x-torrent.row
                        :$torrent
                        :meta="$torrent->meta"
                        :personalFreeleech="$personal_freeleech"
                    />
                @endforeach
            </tbody>
        </table>
    </div>
</section>
