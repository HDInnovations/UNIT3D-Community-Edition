<section class="panelV2 torrent-bdinfo" x-data="bdinfo">
    <header class="panel__header">
        <h2 class="panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-compact-disc"></i>
            BDInfo
        </h2>
        <div class="panel__actions">
            <div class="panel__action">
                <button class="form__button form__button--text" x-data x-on:click.stop="copy">
                    Copy
                </button>
            </div>
        </div>
    </header>
    <div class="panel__body">
        <div class="bbcode-rendered">
            <pre><code x-ref="bdinfo">{{ $torrent->bdinfo }}</code></pre>
        </div>
    </div>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('alpine:init', () => {
            Alpine.data('bdinfo', () => ({
                copy() {
                    navigator.clipboard.writeText(this.$refs.bdinfo.textContent);
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
