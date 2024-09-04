document.addEventListener('alpine:init', () => {
    Alpine.data('dialogLivewire', () => ({
        showDialog: {
            ['x-on:click.stop']() {
                this.$refs.dialog.showModal();
            },
        },
        dialogElement: {
            ['x-ref']: 'dialog',
        },
        dialogForm: {
            ['x-on:click.outside']() {
                let closest = this.$event.target.closest('dialog');

                if (closest === null || closest === this.$event.target) {
                    this.$refs.dialog.close();
                }
            },
            ['x-on:submit.prevent']() {
                let closest = this.$event.target.closest('dialog');

                if (closest === null || closest === this.$event.target) {
                    this.$refs.dialog.close();
                }
            },
        },
        submitDialogForm: {
            ['x-on:click']() {
                this.$refs.dialog.close();
            },
        },
    }));
});
