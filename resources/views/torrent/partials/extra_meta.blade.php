<div class="panel panel-chat shoutbox torrent-extra-meta"
     x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'recommendations' }" id="tab_wrapper">
    <!-- The tabs navigation -->
    <div class="panel-heading">
        <h4>
            <nav>
                <i class="{{ config("other.font-awesome") }} fa-waveform-path"></i>
                <a :class="{ 'active': 'recommendations' === tab }"
                   @click.prevent="tab = 'recommendations'; window.location.hash = 'recommendations'" href="#">Recommendations</a>
                |
                <a :class="{ 'active': 'collection' === tab }"
                   @click.prevent="tab = 'collection'; window.location.hash = 'collection'" href="#">Collection</a> |
                <a :class="{ 'active': 'playlists' === tab }"
                   @click.prevent="tab = 'playlists'; window.location.hash = 'playlists'" href="#">Playlists</a>
            </nav>
        </h4>
    </div>

    <!-- The tabs content -->
    <div x-show="tab === 'recommendations'">
        @include('torrent.partials.recommendations')
    </div>
    <div x-show="tab === 'collection'">
        @include('torrent.partials.collection')
    </div>
    <div x-show="tab === 'playlists'">
        @include('torrent.partials.playlists')
    </div>
</div>