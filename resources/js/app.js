import './bootstrap';
import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'reverb',
    host: window.location.hostname + ':8080',
});

window.Echo.channel('chat')
    .listen('.MessageSent', (e) => {
        console.log('Message received:', e.message);
    });