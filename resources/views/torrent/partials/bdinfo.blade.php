<section class="panel panel-chat shoutbox torrent-bdinfo">
    <h2 class="panel__heading">
        <i class="{{ config("other.font-awesome") }} fa-compact-disc"></i>
        BDInfo
    </h2>
    <div class="panel__body">
        <div class="bbcode-rendered">
            <pre><code>{{ $torrent->bdinfo }}</code></pre>
        </div>
    </div>
</section>
