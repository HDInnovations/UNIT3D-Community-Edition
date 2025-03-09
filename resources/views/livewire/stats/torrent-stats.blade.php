<section class="panelV2 panel--grid-item">
    <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
    <dl class="key-value">
        @foreach ($categories as $category)
            <div class="key-value__group">
                <dt>{{ $category->name }} {{ __('common.category') }}</dt>
                <dd>{{ $category->torrents_count }}</dd>
            </div>
        @endforeach
    </dl>
    <dl class="key-value">
        @foreach ($resolutions as $resolution)
            <div class="key-value__group">
                <dt>{{ $resolution->name }} {{ __('common.resolution') }}</dt>
                <dd>{{ $resolution->torrents_count }}</dd>
            </div>
        @endforeach
    </dl>
    <dl class="key-value">
        <div class="key-value__group">
            <dt>{{ __('stat.total-torrents') }}</dt>
            <dd>{{ $num_torrent }}</dd>
        </div>
        <div class="key-value__group">
            <dt>{{ __('stat.total-torrents') }} {{ __('torrent.size') }}</dt>
            <dd>{{ App\Helpers\StringHelper::formatBytes($torrent_size, 2) }}</dd>
        </div>
    </dl>
</section>
