<section
    class="panelV2"
    x-data="{
        tab: window.location.hash ? window.location.hash.substring(1) : 'recommendations',
    }"
    id="tab_wrapper"
>
    <!-- The tabs navigation -->
    <h2 class="panel__heading">Relations</h2>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'recommendations' && 'panel__tab--active'"
            x-on:click="tab = 'recommendations'; window.location.hash = 'recommendations'"
        >
            Recommendations
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'collection' && 'panel__tab--active'"
            x-on:click="tab = 'collection'; window.location.hash = 'collection'"
        >
            Collection
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'playlists' && 'panel__tab--active'"
            x-on:click="tab = 'playlists'; window.location.hash = 'playlists'"
        >
            Playlists
        </li>
    </menu>
    <!-- The tabs content -->
    <div x-show="tab === 'recommendations'">
        @include('torrent.partials.recommendations')
    </div>
    <div x-show="tab === 'collection'" x-cloak>
        @include('torrent.partials.collection')
    </div>
    <div x-show="tab === 'playlists'" x-cloak>
        @include('torrent.partials.playlists')
    </div>
</section>
