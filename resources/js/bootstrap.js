import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: pusherKey,
    cluster: pusherCluster ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${pusherCluster}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel('order-placed')
    .listen('RTOrderPlacedNotificationEvent', (e) => {
        console.log(e);
        let html = `  <a href="/admin/orders/${e.order_id}" class="dropdown-item">
        <div class="dropdown-item-icon bg-info text-white">
            <i class="fas fa-bell"></i>
        </div>
        <div class="dropdown-item-desc">
            ${e.message}
            <div class="time">${new Date(e.created_at).toLocaleString()}</div>
        </div>
    </a>`;
        $(".rt_notification").prepend(html);
        $('.notification_beep').addClass('beep');
    });
