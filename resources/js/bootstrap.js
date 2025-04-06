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
    forceTLS: true
});

// Écouter l'événement  
 
var channel = Echo.channel('blood-requests');
channel.listen('.new-blood-request', function(data) {
    alert(JSON.stringify(data));
});