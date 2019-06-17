<div class="button-holder">
    <div class="button-left">
        <a href="{{ route('categories') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-file"></i> @lang('torrent.categories')
        </a>
        <a href="{{ route('cards') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-image"></i> @lang('torrent.cards')
        </a>
        <a href="{{ route('groupings') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-clone"></i> @lang('torrent.groupings')
        </a>
        <a href="{{ route('torrents') }}" class="btn btn-sm btn-primary">
            <i class="{{ config('other.font-awesome') }} fa-list"></i> @lang('torrent.list')
        </a>
        <a href="{{ route('rss.index') }}" class="btn btn-sm btn-warning">
            <i class="{{ config('other.font-awesome') }} fa-rss"></i> @lang('rss.rss') @lang('rss.feeds')
        </a>
    </div>
    <div class="button-right">
        <a href="#/" class="btn btn-sm btn-danger" id="facetedFiltersToggle">
            <i class="{{ config('other.font-awesome') }} fa-sliders-h"></i> @lang('torrent.filters')
        </a>
    </div>
</div>
