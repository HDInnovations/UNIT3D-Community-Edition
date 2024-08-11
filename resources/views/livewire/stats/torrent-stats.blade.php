<section class="panelV2 panel--grid-item">
    <h2 class="panel__heading">{{ __('torrent.torrents') }}</h2>
    <dl class="key-value">
        @foreach ($categories as $category)
            <div class="key-value__group">
                <dt>{{ $category->name }} {{ __('common.category') }}</dt>
                <dd>{{ $category->torrents_count }}</dd>
            </div>
        @endforeach

        <div class="key-value__group">
            <dt>HD</dt>
            <dd>{{ $num_hd }}</dd>
        </div>
        <div class="key-value__group">
            <dt>SD</dt>
            <dd>{{ $num_sd }}</dd>
        </div>
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
