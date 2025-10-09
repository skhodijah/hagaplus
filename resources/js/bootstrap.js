import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Import Laravel Echo
import Echo from "laravel-echo";

// Import Pusher (if using Pusher)
import Pusher from "pusher-js";

// Configure Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY || "your-pusher-key",
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || "mt1",
    wsHost:
        import.meta.env.VITE_PUSHER_HOST ||
        `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME || "https") === "https",
    enabledTransports: ["ws", "wss"],
    encrypted: true,
    disableStats: true,
});
