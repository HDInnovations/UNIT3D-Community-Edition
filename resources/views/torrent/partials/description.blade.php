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
                    x-on:click.stop="
                        navigator.clipboard.writeText(atob('{{ base64_encode($torrent->description) }}'));
                        Swal.fire({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000,
                              icon: 'success',
                              title: 'Copied to clipboard!'
                        })
                    "
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
