import lodash from 'lodash';
window._ = lodash;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
};

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Sweet Alert
import Swal from 'sweetalert2';
window.Swal = Swal;

// Vite
import.meta.glob([
    '/public/img/pipes/**',
    '/resources/sass/vendor/webfonts/font-awesome/**',
]);

// Livewire + AlpineJS
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm.js';

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

Livewire.start();