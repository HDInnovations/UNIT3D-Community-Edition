/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import Echo from 'laravel-echo';

import client from 'socket.io-client';

window.io = client;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: import.meta.env.VITE_ECHO_ADDRESS,
    forceTLS: true,
    withCredentials: true,
    transports: ['websocket'],
    enabledTransports: ['wss'],
});

