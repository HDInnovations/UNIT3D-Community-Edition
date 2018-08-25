window._ = require('lodash')

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  // Note: Eventually we will end up 100% jQuery free with the conversion to VueJS
  window.$ = window.jQuery = require('jquery')

  require('bootstrap-sass')
} catch (e) {}

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')
  }
});

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

//

import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: window.location.hostname + ':6001'
});



/**
 * UNIT3D
 */
require('select2')
window.Ladda = require('ladda')

/*
 * jQuery Extensions
 *
 * Note: Eventually we will end up 100% jQuery free with the conversion to VueJS
 */
require('jquery-textcomplete')
require('jquery.flot')

// countUp JS from npm
window.CountUp = require('countup.js')

// wysibb editor
require('./wysibb/jquery.wysibb')

// emojis
window.emoji = require('./unit3d/emoji')

window.Raphael = require('raphael');
window.swal = require('sweetalert2')
window.toastr = require('toastr')
