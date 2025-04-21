import axios from 'axios'; 
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '3abca8d045835fc7ea6d',
    cluster: 'eu',
    forceTLS: false,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            Authorization: 'Bearer 120|nwdHp8KZHWx91EmY8pdyOkzFeqVaLzCJFOqSBXY814625bc9' // si tu stockes le token ici
        }
    }
});

// Écouter l'événement  
 
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
