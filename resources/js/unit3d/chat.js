/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import Echo from 'laravel-echo';

import io from 'socket.io-client';
window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: process.env.MIX_ECHO_ADDRESS,
    transports: ['websocket', 'polling', 'flashsocket'],
});
