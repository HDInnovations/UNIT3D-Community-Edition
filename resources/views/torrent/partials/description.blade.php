<section class="panelV2">
    <header class="panel__header">
        <h2 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-sticky-note"></i>
            {{ __('common.description') }}
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button
                    class="form__button form__button--text"
                    x-data="description"
                    x-on:click.stop="copy"
                >
                    Copy
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body bbcode-rendered">
        @joypixels($torrent->getDescriptionHtml())
    </div>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('alpine:init', () => {
            Alpine.data('description', () => ({
                copy() {
                    text = document.createElement('textarea');
                    text.innerHTML = decodeURIComponent(
                        atob('{{ base64_encode($torrent->description) }}')
                            .split('')
                            .map((c) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
                            .join(''),
                    );
                    navigator.clipboard.writeText(text.value);
                    butterup.toast({
                        title: '🎉 Hooray!',
                        message: 'Copied to clipboard!',
                        location: 'top-right',
                        dismissable: false,
                        theme: 'glass',
                        type: 'success',
                    });
                },
            }));
        });
    </script>
</section>
