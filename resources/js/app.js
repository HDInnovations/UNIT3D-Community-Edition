/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/* Plugins*/
import VTooltip from 'v-tooltip';
Vue.use(VTooltip);

/* Components */
Vue.component('version', require('./components/Version').default);
Vue.component('chatbox', require('./components/chat/Chatbox').default);
Vue.component('bookmark', require('./components/BookmarkButton').default);
Vue.component('smallbookmark', require('./components/SmallBookmarkButton').default);

const app = new Vue({
    el: '#app',
});
