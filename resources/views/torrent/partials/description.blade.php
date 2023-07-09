<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">
            <i class="{{ config("other.font-awesome") }} fa-sticky-note"></i>
            {{ __('common.description') }}
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button
                    class="form__button form__button--text"
                    x-data
                    x-on:click.stop="navigator.clipboard.writeText(atob('{{ base64_encode($torrent->description) }}'))"
                >
                    Copy
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body bbcode-rendered">
        @joypixels($torrent->getDescriptionHtml())
    </div>
</section>
