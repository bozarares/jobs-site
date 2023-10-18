import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

let laravelEcho = null;

function broadcastListen(id) {
    if (laravelEcho) {
        laravelEcho.disconnect();
        laravelEcho = null;
    }
    laravelEcho = new Echo({
        broadcaster: 'pusher',
        key: 12345,
        cluster: 'mt1',
        wsHost: 'jobs.test',
        wsPort: 6001,
        wssPort: 6001,
        forceTLS: true,
        encrypted: true,
        enabledTransports: ['ws', 'wss'],
    });
    laravelEcho
        .private(`App.Models.User.${id}`)
        .notification((notification) => {
            console.log(notification.message);
        });
}

function broadcastDisconnect() {
    if (laravelEcho) {
        laravelEcho.disconnect();
        laravelEcho = null;
    }
}

export { broadcastListen, broadcastDisconnect };
