import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.data('dialog', () => ({
    showDialog: {
        ['x-on:click.stop']() {
            this.$refs.dialog.showModal();
        }
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
        }
    }
}));

Alpine.data('dialogLivewire', () => ({
    showDialog: {
        ['x-on:click.stop']() {
            this.$refs.dialog.showModal();
        }
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
        }
    },
    submitDialogForm: {
        ['x-on:click']() {
            this.$refs.dialog.close();
        }
    }
}));

Alpine.data('toggle', () => ({
    toggleState: false,
    isToggledOn() {
        return this.toggleState === true;
    },
    isToggledOff() {
        return this.toggleState === false;
    },
    toggle() {
        this.toggleState = !this.toggleState
    },
    toggleOn() {
        this.toggleState = true;
    },
    toggleOff() {
        this.toggleState = false;
    }
}))

Alpine.data('checkboxGrid', () => ({
    columnHeader: {
        ['x-on:click']() {
            let cellIndex = this.$el.cellIndex + 1;
            let cells = this.$root.querySelectorAll(
              `tbody tr td:nth-child(${cellIndex}) > input[type="checkbox"]`,
            );

            if (Array.from(cells).some((el) => el.checked)) {
                cells.forEach((el) => (el.checked = false));
            } else {
                cells.forEach((el) => (el.checked = true));
            }
        },
        ['x-bind:style']() {
            return {
                cursor: 'pointer',
            };
        },
    },
    rowHeader: {
        ['x-on:click']() {
            let rowIndex = this.$el.parentElement.sectionRowIndex + 1;
            let cells = this.$root.querySelectorAll(
              `tbody tr:nth-child(${rowIndex}) td > input[type="checkbox"]`,
            );

            if (Array.from(cells).some((el) => el.checked)) {
                cells.forEach((el) => (el.checked = false));
            } else {
                cells.forEach((el) => (el.checked = true));
            }
        },
        ['x-bind:style']() {
            return {
                cursor: 'pointer',
            };
        },
    },
}));

Alpine.start();
