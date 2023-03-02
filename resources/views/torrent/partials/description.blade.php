<section class="panelV2">
    <h2 class="panel__heading">
        <i class="{{ config("other.font-awesome") }} fa-sticky-note"></i>
        {{ __('common.description') }}
    </h2>
    <div class="panel__body bbcode-rendered">
        @joypixels($torrent->getDescriptionHtml())
    </div>
</section>
