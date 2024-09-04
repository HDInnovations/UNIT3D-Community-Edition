document.addEventListener('alpine:init', () => {
    Alpine.data('dialog', () => ({
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
        },
    }));
});
