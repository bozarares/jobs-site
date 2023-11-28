import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { useUserStore } from './Stores/userStore';
window.Pusher = Pusher;

let laravelEcho = null;

function broadcastListen(id) {
    if (laravelEcho) {
        return;
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
        .notification(async (notification) => {
            const userStore = useUserStore();
            userStore.currentUser.initializeNotifications();
        });
}

function broadcastDisconnect() {
    if (laravelEcho) {
        laravelEcho.disconnect();
        laravelEcho = null;
    }
}

export { broadcastDisconnect, broadcastListen };
